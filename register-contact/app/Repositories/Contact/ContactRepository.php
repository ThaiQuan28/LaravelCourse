<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use App\Repositories\Contact\ContactInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactInterface
{
    protected $model;

    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    /**
     * Lấy tất cả contacts
     */
    public function getAll(): Collection
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Lấy contacts với phân trang
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Lấy contact theo ID
     */
    public function getById(int $id): ?Contact
    {
        return $this->model->find($id);
    }

    /**
     * Tạo contact mới
     */
    public function create(array $data): Contact
    {
        return $this->model->create($data);
    }

    /**
     * Cập nhật contact
     */
    public function update(int $id, array $data): ?Contact
    {
        $contact = $this->getById($id);
        if ($contact) {
            $contact->update($data);
            return $contact->fresh();
        }
        return null;
    }

    /**
     * Xóa contact
     */
    public function delete(int $id): bool
    {
        $contact = $this->getById($id);
        if ($contact) {
            return $contact->delete();
        }
        return false;
    }

    /**
     * Tìm kiếm contacts theo tên hoặc email
     */
    public function search(string $keyword): Collection
    {
        return $this->model->where(function ($query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%");
        })->orderBy('created_at', 'desc')->get();
    }

    /**
     * Tìm kiếm contacts với phân trang
     */
    public function searchPaginated(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where(function ($query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%");
        })->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = $this->model->where('email', $email);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Đếm tổng số contacts
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Lấy thống kê contacts
     */
    public function getStats(): array
    {
        return [
            'total_contacts' => $this->model->count(),
            'contacts_with_phone' => $this->model->whereNotNull('phone')->count(),
            'contacts_with_address' => $this->model->whereNotNull('address')->count(),
            'recent_contacts' => $this->model->where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }
}