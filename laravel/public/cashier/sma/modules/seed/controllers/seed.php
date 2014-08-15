<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seed extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		if ($this->checkPremium() === false)
		{
			die('Sorry old fart. What get you here?');
		}

		$this->getOwnerId();

		if ($this->isInstallable() === false)
		{
			$this->redirect();
			exit;
		}
	}

	public function getOwnerId()
	{
		@session_start();
		if (!isset($_SESSION['owner_id'])) {
			die('You must login to use this feature');
		}

		$this->ownerId = (int) $_SESSION['owner_id'];
	}

	public function isInstallable()
	{
		$query = $this->db->where('owner_id', $this->ownerId)
			->where('username', $_SESSION['session_loginname'])
			->get('users');
		return $query->num_rows() <= 0;
	}

	protected function auto_login($user)
	{
		// Auto-login
		$this->load->config('auth/ion_auth', true);
		$key = $this->config->item('identity', 'ion_auth');

		$identity = $user->$key;
		$password = $this->session->userdata('temp_password');
		$a = Modules::run('auth/auth/_auto_login', $identity, $password);
	}

	/**
	 * Seed some required data
	 *
	 * @return void
	 */
	public function index()
	{
		$user = $this->seedUser();
		$this->auto_login($user);

		// Seed warehouse
		$warehouseId = $this->seed('warehouses', array(
			'code'    => 'WHI',
			'name'    => 'Warehouse 1',
			'address' => 'Address',
			'city'    => 'City'
		));

		$billerId = $this->seed('billers', array(
			'name'           => 'Mian Saleem',
			'company'        => 'Tecdiary IT Solutions',
			'address'        => 'Address',
			'city'           => 'City',
			'state'          => 'Sate',
			'postal_code'    => '0000',
			'country'        => 'Malaysia',
			'phone'          => '012345678',
			'email'          => 'saleem@tecdairy.com',
			'logo'           => 'logo.png',
			'invoice_footer' => '',
			'cf1'            => '',
			'cf2'            => '',
			'cf3'            => '',
			'cf4'            => '',
			'cf5'            => '',
			'cf6'            => '',
		));

		$categoryId = $this->seed('categories', array(
			'code' => 'C1',
			'name' => 'Category 1',
		));

		$subcategoryId = $this->seed('subcategories', array(
			'category_id' => $categoryId,
			'code'        => 'Sub-c1',
			'name'        => 'sub category 1'
		));

		$this->seed('comment', array(
			'comment' => '&lt;h4&gt;Thank you for Purchasing Stock Manager Advance 2.3 with POS Module &lt;/h4&gt;\r\n&lt;p&gt;\r\n              This is latest the latest release of Stock Manager Advance.\r\n&lt;/p&gt;'
		));

		$customerId = $this->seed('customers', array(
			'name'          => 'Test Customer',
			'company'       => 'Customer Company Name',
			'address'       => 'Customer Address',
			'city'          => 'Petaling Jaya',
			'state'         => 'Selangor',
			'postal_code'   => '46000',
			'country'       => 'Malaysia',
			'phone'         => '0123456789',
			'email'         => 'customer@tecdiary.com',
			'giftcard_code' => 1396966267,
			'giftcard'      => 418,
			'cf1'           => '',
			'cf2'           => '',
			'cf3'           => '',
			'cf4'           => '',
			'cf5'           => '',
			'cf6'           => '',
		));

		$this->seed('pos_settings', array(
			'cat_limit'        => 22,
			'pro_limit'        => 20,
			'default_category' => $categoryId,
			'default_customer' => $customerId,
			'default_biller'   => $billerId,
			'display_time'     => 'yes',
			'cf_title1'        => '',
			'cf_title2'        => '',
			'cf_value1'        => '',
			'cf_value2'        => ''
		));


		$supplierId = $this->seed('suppliers', array(
			'name'        => 'Test Supplier',
			'company'     => 'Supplier Company Name',
			'address'     => 'Supplier Address',
			'city'        => 'Petaling Jaya',
			'state'       => 'Selangor',
			'postal_code' => '46050',
			'country'     => 'Malaysia',
			'phone'       => '0123456789',
			'email'       => 'supplier@tecdiary.com',
			'cf1'         => '-',
			'cf2'         => '-',
			'cf3'         => '-',
			'cf4'         => '-',
			'cf5'         => '-',
			'cf6'         => '-'
		));

		$taxRateId2 = $this->seed('tax_rates', array(
			'name' => 'No Tax',
			'rate' => 0.00,
			'type' => '2'
		));

		$taxRateId = $this->seed('tax_rates', array(
			'name' => 'VAT',
			'rate' => 24.00,
			'type' => '1'
		));

		$this->seed('tax_rates', array(
			'name' => 'GST',
			'rate' => 6.00,
			'type' => '1'
		));

		$discountId = $this->seedDiscount();
		$dateFormatId = $this->seedDateFormat();
		$this->seed('settings', array(
			'logo'                 => 'header_logo.png',
			'logo2'                => 'login_logo.png',
			'site_name'            => 'Stock Manager Advance',
			'language'             => 'finland',
			'default_warehouse'    => $warehouseId,
			'currency_prefix'      => 'USD',
			'default_invoice_type' => $taxRateId, // Start
			'default_tax_rate'     => $taxRateId,
			'default_tax_rate2'    => $taxRateId2,
			'default_discount'     => $discountId,
			'dateformat'           => $dateFormatId, // End
			'tax1'                 => 1,
			'tax2'                 => 1,
			'discount_option'      => 2,
			'discount_method'      => 2,
			'rows_per_page'        => 10,
			'no_of_rows'           => 9,
			'total_rows'           => 30,
			'product_serial'       => 1,
			'version'              => '2.3',
			'sales_prefix'         => 'SL',
			'quote_prefix'         => 'QU',
			'purchase_prefix'      => 'PO',
			'transfer_prefix'      => 'TR',
			'barcode_symbology'    => 'code128',
			'theme'                => 'blue',
			'restrict_sale'        => 0,
			'restrict_user'        => 0,
			'restrict_calendar'    => 0,
			'bstatesave'           => 1
		));

		$this->redirect();
	}

	/**
	 * Did you pay for this service?
	 *
	 * @return bool
	 */
	protected function checkPremium()
	{
		// @todo: Implement
		return true;
	}

	protected function seed($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	protected function seedUser()
	{
		// Check if this user has been seeded
		$user = $this->db->where('owner_id', $this->ownerId)
			->get('users')
			->row();

		// Cannot find user
		if (empty($user))
		{
			// Insert
			$sql = 'SELECT * FROM varaa_users WHERE id = ?';
			$result = $this->db->query($sql, array($this->ownerId))->row();
			if (empty($result))
			{
				die('Cannot find user with ID #'.$this->ownerId);
			}
			
			// Seeding groups also
			$groupId = $this->seedGroup();

			// Create new user
			$password = uniqid();
			// Set password into session, force user to change password
			// IMMEDIATELY
			$this->session->set_userdata(array(
				'temp_password' => $password
			));
			$userId = Modules::run(
				'auth/auth/_create_user',
				$result->username,
				$password,
				$result->email,
				array(
                    'first_name'              => $result->first_name,
                    'last_name'               => $result->last_name,
                    'forgotten_password_code' => $password
				),
				array (
					$groupId
				)
			);

			// Get user, again
			$user = $this->db->where('id', $userId)->get('users')->row();
		}

		return $user;
	}

	protected function seedDateFormat()
	{
		$this->seed('date_format', array (
			'js' => 'mm-dd-yyyy',
			'php' => 'm-d-Y',
			'sql' => '%m-%d-%Y',
		));

		$this->seed('date_format', array (
			'js' => 'mm/dd/yyyy',
			'php' => 'm/d/Y',
			'sql' => '%m/%d/%Y',
		));

		$this->seed('date_format', array (
			'js' => 'mm.dd.yyyy',
			'php' => 'm.d.Y',
			'sql' => '%m.%d.%Y',
		));

		$this->seed('date_format', array (
			'js' => 'dd-mm-yyyy',
			'php' => 'd-m-Y',
			'sql' => '%d-%m-%Y',
		));

		$dateFormatId = $this->seed('date_format', array (
			'js' => 'dd/mm/yyyy',
			'php' => 'd/m/Y',
			'sql' => '%d/%m/%Y',
		));

		$this->seed('date_format', array (
			'js' => 'dd.mm.yyyy',
			'php' => 'd.m.Y',
			'sql' => '%d.%m.%Y',
		));
		return $dateFormatId;
	}

	protected function seedDiscount()
	{
		$discountId = $this->seed('discounts', array(
			'name'     => 'No Discount',
			'discount' => 0.00,
			'type'     => '2'
		));

		$this->seed('discounts', array(
			'name'     => '2.5 Percent',
			'discount' => 2.50,
			'type'     => '1'
		));

		$this->seed('discounts', array(
			'name'     => '5 Percent',
			'discount' => 5.00,
			'type'     => '1'
		));

		$this->seed('discounts', array(
			'name'     => '10 Percent',
			'discount' => 10.00,
			'type'     => '1'
		));

		return $discountId;
	}

	protected function seedGroup()
	{
		$groupId = $this->seed('groups', array(
			'name' => 'owner',
			'description' => 'Owner'
		));

		$this->seed('groups', array(
			'name' => 'admin',
			'description' => 'Administrator'
		));

		$this->seed('groups', array(
			'name' => 'purchaser',
			'description' => 'Purchasing Staff'
		));

		$this->seed('groups', array(
			'name' => 'salesman',
			'description' => 'Sales Staff'
		));

		$this->seed('groups', array(
			'name' => 'viewer',
			'description' => 'View Only User'
		));

		return $groupId;
	}

	protected function redirect($endpoint = null)
	{
		return redirect(($endpoint !== null)
			? $endpoint
			: site_url().'?module=auth&view=change_password');
	}

}
