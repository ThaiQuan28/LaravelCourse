<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Repositories\Contact\ContactInterface;
use App\Http\Requests\StorePostContact;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Requests\SearchContactRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmail;
use Exception;

class ContactController extends Controller
{
    protected $contactRepository;

    public function __construct(ContactInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Lấy danh sách tất cả contacts
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 15);
            
            if ($request->has('paginated') && $request->query('paginated') === 'true') {
                $contacts = $this->contactRepository->getPaginated($perPage);
                return response()->json([
                    'status' => 'success',
                    'data' => $contacts->items(),
                    'pagination' => [
                        'current_page' => $contacts->currentPage(),
                        'per_page' => $contacts->perPage(),
                        'total' => $contacts->total(),
                        'last_page' => $contacts->lastPage(),
                        'from' => $contacts->firstItem(),
                        'to' => $contacts->lastItem(),
                    ],
                    'message' => 'Lấy danh sách contacts thành công'
                ]);
            }

            $contacts = $this->contactRepository->getAll();
            return response()->json([
                'status' => 'success',
                'data' => $contacts,
                'message' => 'Lấy danh sách contacts thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách contacts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo contact mới
     */
    public function store(StorePostContact $request): JsonResponse
    {
        try {
            $validated = $request->safe()->only(['name', 'email', 'phone', 'address']);
            $contact = $this->contactRepository->create($validated);
            
            return response()->json([
                'status' => 'success',
                'data' => $contact,
                'message' => 'Tạo contact thành công'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo contact: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Lấy thông tin contact theo ID
     */
    public function show(Contact $contact): JsonResponse
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => $contact,
                'message' => 'Lấy thông tin contact thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy thông tin contact: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật contact
     */
    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        try {
            $validated = $request->safe()->only(['name', 'email', 'phone', 'address']);
            $updatedContact = $this->contactRepository->update($contact->id, $validated);         
            return response()->json([
                'status' => 'success',
                'data' => $updatedContact,
                'message' => 'Cập nhật contact thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật contact: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Xóa contact
     */
    public function destroy(Contact $contact): JsonResponse
    {
        try {
            $this->contactRepository->delete($contact->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa contact thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi xóa contact: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tìm kiếm contacts
     */
    public function search(SearchContactRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $keyword = $validated['q'];
            $paginated = ($validated['paginated'] ?? 'false') === 'true';
            
            if ($paginated) {   
                $perPage = $validated['per_page'] ?? 15;
                $contacts = $this->contactRepository->searchPaginated($keyword, $perPage);
                return response()->json([
                    'status' => 'success',
                    'data' => $contacts->items(),
                    'pagination' => [
                        'current_page' => $contacts->currentPage(),
                        'per_page' => $contacts->perPage(),
                        'total' => $contacts->total(),
                        'last_page' => $contacts->lastPage(),
                        'from' => $contacts->firstItem(),
                        'to' => $contacts->lastItem(),
                    ],
                    'message' => 'Tìm kiếm contacts thành công'
                ]);
            }

            $contacts = $this->contactRepository->search($keyword);
            
            return response()->json([
                'status' => 'success',
                'data' => $contacts,
                'message' => 'Tìm kiếm contacts thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tìm kiếm contacts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thống kê contacts
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->contactRepository->getStats();
            
            return response()->json([
                'status' => 'success',
                'data' => $stats,
                'message' => 'Lấy thống kê contacts thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy thống kê contacts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị trang quản lý contacts (Web)
     */
    public function indexWeb()
    {
        return view('contacts.index');
    }

    /**
     * Gửi email cho contact qua SMTP
     */
    public function sendEmail(Request $request, Contact $contact): JsonResponse
    {
        try {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:5000',
            ]);
            // Ensure the contact has a valid recipient email
            if (empty($contact->email)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contact không có địa chỉ email hợp lệ',
                ], 422);
            }

            $mailable = new ContactEmail($contact, $validated['subject'], $validated['message']);

            // Send to contact's email, optionally cc HR if configured
            $mail = Mail::to($contact->email);
            if (config('mail.hr_email')) {
                $mail->cc(config('mail.hr_email'));
            }
            $mail->send($mailable);
            return response()->json([
                'status' => 'success',
                'message' => 'Gửi email thành công'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gửi email thất bại: ' . $e->getMessage(),
            ], 500);
        }
    }

}