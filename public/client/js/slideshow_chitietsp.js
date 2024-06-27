$(document).ready(function () {
    // 1.Xử lý xử kiện khi nhấp vào ảnh thumb
    $(".list-thumb .thumb-item").click(function () {
        let picture_src = $(this).find("img").attr("src");
        $(".show-picture img").attr("src", picture_src);

        $(".list-thumb .thumb-item").removeClass("active");
        $(this).addClass("active");

    });
});
//2.Xử lý nhấp vào next và prev
$(document).ready(function () {
    $(".slider-nav .next-btn").click(function () {
        if ($(".list-thumb .thumb-item:last-child").hasClass("active")) {
            $(".list-thumb .thumb-item:first-child").click();
        } else {
            $(".list-thumb .thumb-item.active").next().click();
        };
    });

    $(".slider-nav .prev-btn").click(function () {
        if ($(".list-thumb .thumb-item:first-child").hasClass("active")) {
            $(".list-thumb .thumb-item:last-child").click();
        } else {
            $(".list-thumb .thumb-item.active").prev().click();
        };
    });
    // Active phần tử ảnh thumb đầu tiên
    $(".list-thumb .thumb-item:first-child").click();
});


// Jquery cho màu, size 
$(document).ready(function () {
    // Xử lý khi click vào màu sắc
    $(".mau_sac ul li a").click(function (e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của thẻ a

        $(".mau_sac ul li a").removeClass("selected"); // Xóa lớp selected từ tất cả các màu khác
        $(this).addClass("selected"); // Thêm lớp selected vào màu được chọn
    });

    // Xử lý khi click vào size
    $(".size_sp ul li a").click(function (e) {
        e.preventDefault();

        $(".size_sp ul li a").removeClass("selected");
        $(this).addClass("selected");
    });
});


// Jquery thêm sp vào cart 


// Sử dụng jQuery
// $(document).ready(function () {
//     $('.mau_sac ul li a').on('click', function (event) {
//         event.preventDefault();
//         var selectedColor = $(this).attr('class').split(' ')[0]; // Lấy class đầu tiên
//         $('#selected_color_id').val(selectedColor);
//     });

//     $('.size_sp ul li a').on('click', function (event) {
//         event.preventDefault();
//         var selectedSize = $(this).text();
//         $('#selected_size_id').val(selectedSize);
//     });

//     $('input[name="so_luong"]').on('change', function () {
//         var selectedQuantity = $(this).val();
//         $('#selected_quantity').val(selectedQuantity);
//     });
// });
////////////////////////////////////////////////////////////////
// $(document).ready(function () {
//     $('.mau_sac ul li a').on('click', function (event) {
//         event.preventDefault();
//         var selectedColor = $(this).attr('class').split(' ')[0]; // Lấy class đầu tiên
//         var selectedBtSanpham = $(this).data('id-bt-sanpham'); // Lấy giá trị id_bt_sanpham từ data attribute
//         $('#selected_color_id').val(selectedColor);
//         $('#selected_bt_sanpham').val(selectedBtSanpham); // Lưu id_bt_sanpham vào input hidden
//     });

//     $('.size_sp ul li a').on('click', function (event) {
//         event.preventDefault();
//         var selectedSize = $(this).text();
//         var selectedBtSanpham = $(this).data('id-bt-sanpham'); // Lấy giá trị id_bt_sanpham từ data attribute
//         $('#selected_size_id').val(selectedSize);
//         $('#selected_bt_sanpham').val(selectedBtSanpham); // Lưu id_bt_sanpham vào input hidden
//     });

//     $('input[name="so_luong"]').on('change', function () {
//         var selectedQuantity = $(this).val();
//         $('#selected_quantity').val(selectedQuantity);
//     });
// });



