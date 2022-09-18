<?php


namespace Phangia\App;
use Phangia\App\Enum;

class Config {

	public $settings;
	protected static $instance = null;
	private function __construct() {
		$this->settings = get_option( Enum::THEME_OPTIONS );
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_config( $name ) {
		return $this->settings[ $name ] ?? '';
	}

	public function get_list() {
		$text_fields = [
			[
				'name' => 'facebook'
			],
			[
				'name' => 'gmail',
				'description' => 'Ví dụ: phanvuhoang@gmail.com'
			],
			[
				'name' => 'twitter'
			],
			[
				'name' => 'youtube'
			],
			[
				'name' => 'company_text',
				'description' => 'Ví dụ: CÔNG TY TNHH PHAN GIA',
				'default' => 'CÔNG TY TNHH ĐẦU TƯ THƯƠNG MẠI DỊCH VỤ ÁNH SAO' /*@todo*/
			],
			[
				'name' => 'address',
				'description' => 'Ví dụ: VPGD: 702/59A Lê Đức Thọ, Phường 15, Quận Gò Vấp'
			],
			[
				'name' => 'phone',
				'description' => 'Số máy bàn'
			],
//			[
//				'name' => 'phone2'
//			],
			[
				'name' => 'phone3',
				'description' => 'Số phone này hiển thị ở Banner Header'
			],
//			[
//				'name' => 'hotline'
//			],
//			[
//				'name' => 'hotline2'
//			],
			[
				'name' => 'email'
			],
			[
				'name' => 'web',
				'description' => 'Ví dụ: www.maylanhanhsao.com'
			],
			[
				'name' => 'web2',
				'description' => 'Ví dụ: www.thicongmaylanh.com'
			],
			[
				'name' => 'copyright',
				'description' => 'Ví dụ: Copyright © 2017 Công Ty TNHH Điện Lạnh Ánh Sao. Design by Nina Co., Ltd'
			],
			[
				'name' => 'bg_header',
				'description' => 'Kích thước ảnh thỏa mãn: https://via.placeholder.com/1389x138'
			],
			[
				'name' => 'banner_header',
				'description' => 'Kích thước ảnh thỏa mãn: https://via.placeholder.com/765x81'
			],
			[
				'name' => 'load_config',
				'description' => 'Hãy điền giá trị Yes hoặc No. Nếu Yes => Lấy value trong db, nếu No => Lấy value từ Enum'
			],
			[
				'name' => 'primary_menu'
			],
			[
				'name' => 'menu_links'
			],
			[
				'name' => 'footer_menu_3'
			],
			[
				'name' => 'footer_menu_2'
			],
			[
				'name' => 'footer_menu'
			],
			[
				'name' => 'img_phone_call'
			],
//			[
//				'name' => 'tin_tuc'
//			],
//			[
//				'name' => 'thi_cong_ong_gio'
//			],
//			[
//				'name' => 'thi_cong_ong_dong'
//			],
//			[
//				'name' => 'lap_dat_may_lanh'
//			],
//			[
//				'name' => 'bao_tri_ve_sinh'
//			],
			[
				'name' => 'page_tin_tuc',
				'description' => 'Điền post id của trang tin tức'
			],
			[
				'name' => 'page_thi_cong_ong_gio',
				'description' => 'Post ID của page tương ứng.'
			],
			[
				'name' => 'page_thi_cong_ong_dong',
				'description' => 'Post ID của page tương ứng.'
			],
			[
				'name' => 'page_san_pham_noi_bat',
				'description' => 'Post ID của page tương ứng.'
			],
			[
				'name' => 'page_lien_he',
				'description' => 'Post ID của page tương ứng.'
			],
			[
				'name' => 'page_lap_dat_may_lanh',
				'description' => 'Post ID của page tương ứng.'
			],
			[
				'name' => 'page_gioi_thieu',
				'description' => 'Post ID của page tương ứng.'
			],
			[
				'name' => 'page_bao_tri_ve_sinh',
				'description' => 'Post ID của page tương ứng.'
			]
		];

		return $text_fields;
	}

}