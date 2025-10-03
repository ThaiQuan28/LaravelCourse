<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchContactRequest;
use App\Http\Requests\Student\StudentFormRequest;
use App\Models\Student;
use App\Repositories\Student\StudentInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class StudentController extends Controller
{
    protected $studentRepository;

    public function __construct(StudentInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Lấy danh sách tất cả contacts
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 15);
            
            if ($request->has('paginated') && $request->query('paginated') === 'true') {
                $contacts = $this->studentRepository->getPaginated($perPage);
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

            $contacts = $this->studentRepository->getAll();
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
    public function store(StudentFormRequest $request): JsonResponse
    {
        try {
            $validated = $request->safe()->only(['name', 'email', 'phone', 'address']);
            $contact = $this->studentRepository->create($validated);
            
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
    public function show(Student $student): JsonResponse
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => $student,
                'message' => 'Lấy thông tin student thành công'
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
    public function update(StudentFormRequest $request, Student $student): JsonResponse
    {
        try {
            $validated = $request->safe()->only(['name','phone', 'address']);
            $updatedStudent = $this->studentRepository->update($student->id, $validated);         
            return response()->json([
                'status' => 'success',
                'data' => $updatedStudent,
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
    public function destroy(Student $student): JsonResponse
    {
        try {
            $this->studentRepository->delete($student->id);

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
                $students = $this->studentRepository->searchPaginated($keyword, $perPage);
                return response()->json([
                    'status' => 'success',
                    'data' => $students->items(),
                    'pagination' => [
                        'current_page' => $students->currentPage(),
                        'per_page' => $students->perPage(),
                        'total' => $students->total(),
                        'last_page' => $students->lastPage(),
                        'from' => $students->firstItem(),
                        'to' => $students->lastItem(),
                    ],
                    'message' => 'Tìm kiếm contacts thành công'
                ]);
            }

            $students = $this->studentRepository->search($keyword);
            
            return response()->json([
                'status' => 'success',
                'data' => $students,
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
            $stats = $this->studentRepository->getStats();
            
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
        return view('students.index');
    }

   
    
}