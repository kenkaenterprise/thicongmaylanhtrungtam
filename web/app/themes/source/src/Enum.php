<?php

namespace Phangia\App;

/**
 * Variable Name.
 * Interface Enum
 * @package Phangia\App
 */
interface Enum {

	/*
	 * Identifier
	 * */
	const THEME_OPTIONS = 'pgtheme_options';
	const C_FB = 'facebook';
	// Config
	const C_GMAIL = 'gmail';
	const C_TWITTER = 'twitter';
	const C_YOUTUBE = 'youtube';

	// Page
	const P_PRODUCT = 93;
	const P_LAP_DAT_MAY_LANH = 138;
	const P_THI_CONG_ONG_DONG = 152;
	const P_THI_CONG_ONG_GIO = 154;
	const P_BAO_TRI_VE_SINH = 156;
	const P_TIN_TUC = 158;
	const P_CONTACT = 181;
	const P_ABOUT = 188;

	// Category
	const CAT_LAP_DAT_MAY_LANH = 3;
	const CAT_THI_CONG_ONG_DONG = 4;
	const CAT_THI_CONG_ONG_GIO = 5;
	const CAT_BAO_TRI_VE_SINH = 2;
	const CAT_TIN_TUC = 6;

	/*
	 * Post Types.
	*/
	const CPT_PARTNER = 'brand';
	const CPT_SLIDER = 'slider';
	const CPT_FIGURE = 'slider_figure';
	const CPT_PRODUCT = 'product';

	/*
	 * Field Groups
	 * */
	/*Product*/
	const ACF_PRODUCT_NAME = 'name';
	/*Family Health Insight*/
	const ACF_HEALTH_CATEGORY = 'category';
	const ACF_HEALTH_SUB_CATEGORY = 'sub_category';

	/*
	 * Post types.
	 * */
	const POST_TYPE_PRODUCT = 'product';
	/*Family Health Insight*/
	const POSY_TYPE_HEALTH = 'health-insights';

	/*MENUS*/
	const MENU_PRIMARY = 2;
	const MENU_FOOTER = 3;
	const MENU_FOOTER_2 = 4;
	const MENU_FOOTER_3 = 5;
	const MENU_LINKS = 6;

}