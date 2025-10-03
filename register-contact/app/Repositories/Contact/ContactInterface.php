<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactInterface
{
    /**
     * Lấy tất cả contacts
     */
    public function getAll(): Collection;

    /**
     * Lấy contacts với phân trang
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Lấy contact theo ID
     */
    public function getById(int $id): ?Contact;

    /**
     * Tạo contact mới
     */
    public function create(array $data): Contact;

    /**
     * Cập nhật contact
     */
    public function update(int $id, array $data): ?Contact;

    /**
     * Xóa contact
     */
    public function delete(int $id): bool;

    /**
     * Tìm kiếm contacts theo tên hoặc email
     */
    public function search(string $keyword): Collection;

    /**
     * Tìm kiếm contacts với phân trang
     */
    public function searchPaginated(string $keyword, int $perPage = 15): LengthAwarePaginator;

    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function emailExists(string $email, ?int $excludeId = null): bool;

    /**
     * Đếm tổng số contacts
     */
    public function count(): int;

    /**
     * Lấy thống kê contacts
     */
    public function getStats(): array;
}