README - Hướng dẫn tích hợp thay đổi (tiếng Việt)

Mục tiêu: thêm các chức năng:
- Admin duyệt bài (approve/reject).
- Người cho thuê (owner) quản lý tài khoản (gồm avatar) và duyệt đơn thuê.
- Người thuê (renter) gửi đơn xin thuê (form với họ tên, ngày sinh, sđt, email, cccd, quê quán).
- Khi đăng bài, bài ở trạng thái 'pending' chờ admin duyệt.

Các file trong thư mục `modifications/` (đã có trong zip):
- migrations.sql
    -> Chạy file SQL này trong phpMyAdmin (database: phongtro). Nó thêm cột avatar, phone, fullname... vào bảng users,
       thêm cột status, reviewed_by, reviewed_at, owner_id, is_rented vào bảng posts, và tạo bảng rental_requests.
    -> **Lưu ý:** hãy kiểm tra tên bảng/cột tương ứng với project của bạn và sửa nếu khác.

- admin_approve_post.php
    -> Endpoint để admin approve/reject bài. Đặt vào thư mục /admin/ hoặc chỉnh path require.
    -> Truy cập: /admin_approve_post.php?id=POST_ID&action=approve

- landlord_edit_profile.php
    -> Form sửa hồ sơ (avatar upload). Đặt vào /landlord/edit_profile.php hoặc tích hợp vào trang profile hiện tại.
    -> Tạo thư mục /uploads/avatars/ với quyền ghi (writable).

- tenant_request_rent.php
    -> Form mà tenant dùng để gửi đơn xin thuê. Gọi bằng ?post_id=ID.

- landlord_requests.php
    -> Trang để owner xem và chấp nhận/từ chối các yêu cầu thuê.
    -> Khi chấp nhận, bạn cần tích hợp logic chuyển trạng thái phòng (is_rented, owner_id) tùy phần còn lại của project.

- functions_additions.php
    -> Các helper isAdmin/isLandlord/isTenant. Thêm nội dung vào functions.php hiện tại.

Hướng dẫn tích hợp chung:
1) Backup code gốc (nếu chưa backup).
2) Chạy migrations.sql trong phpMyAdmin.
3) Tạo folder /uploads/avatars và cấp quyền ghi.
4) Thêm các snippet PHP vào đúng chỗ (kiểm tra require/include đường dẫn).
5) Thêm nút "Đăng bài" để tạo post với status='pending' – hiện tại bạn có thể sửa form bài viết để set status mặc định 'pending'.
6) Kiểm tra kỹ session user structure ($_SESSION['user']) để đảm bảo có 'id' và 'role'.

Ghi chú font/kiểu:
- Mình không thay đổi CSS/phông chữ. Các file PHP cung cấp chỉ là form/logic gợi ý để bạn chèn vào giao diện hiện có.

Nếu bạn muốn, mình có thể:
- Tích hợp trực tiếp các snippet vào project (sửa file cụ thể) — gửi ok để mình tiếp tục và mình sẽ cập nhật zip.
- Hoặc mình chỉ tạo file hướng dẫn như hiện tại để bạn tự chèn.

