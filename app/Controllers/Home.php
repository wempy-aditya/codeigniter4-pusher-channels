<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Home_model;
use Pusher\Pusher;

class Home extends BaseController
{
	protected $Home_model;

    public function __construct()
    {
        $this->Home_model = new Home_model();
    }

	public function index()
	{
		return view('v_product');
	}

	public function get_product() 
	{
		$data = $this->Home_model->get_product();
		$data1 = json_encode($data);
		echo ($data1);
	}

	public function create() 
	{
		$dataa=[
            'product_name' =>$this->request->getPost('product_name'),
            'product_price' =>$this->request->getPost('product_price'),
		];
		$this->Home_model->insert_product($dataa);

		$options = array(
			'cluster' => 'ap1',
			'useTLS' => true
		);
		$pusher = new Pusher(
			'ba91a26344682a3ae594',
			'9c00807d095834fbc26d',
			'1011316',
			$options
		);
		$data['message'] = 'success';
		$pusher->trigger('my-channel', 'my-event', $data);
	}

	public function update() 
	{
		$product_id = $this->request->getPost('product_id');
		$dataa=[
            'product_name' =>$this->request->getPost('product_name'),
            'product_price' =>$this->request->getPost('product_price'),
		];
		$this->Home_model->update_product($dataa, $product_id);

		$options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
		$pusher = new Pusher(
			'ba91a26344682a3ae594',
			'9c00807d095834fbc26d',
			'1011316',
			$options
		);
		$data['message'] = 'success';
		$pusher->trigger('my-channel', 'my-event', $data);
	}

	public function delete()
    {
		$product_id = $this->request->getPost('product_id');
		$this->Home_model->delete_product($product_id);
		
		$options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
		$pusher = new Pusher(
			'ba91a26344682a3ae594',
			'9c00807d095834fbc26d',
			'1011316',
			$options
		);
		$data['message'] = 'success';
		$pusher->trigger('my-channel', 'my-event', $data);
    }
}
