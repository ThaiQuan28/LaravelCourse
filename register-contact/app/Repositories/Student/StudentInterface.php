<?php

namespace App\Repositories\Student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface StudentInterface
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
    public function getById(int $id): ?Student;

    /**
     * Tạo contact mới
     */
    public function create(array $data): Student;

    /**
     * Cập nhật contact
     */
    public function update(int $id, array $data): ?Student;

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
     * Đếm tổng số contacts
     */
    public function count(): int;

    /**
     * Lấy thống kê contacts
     */
    public function getStats(): array;
}