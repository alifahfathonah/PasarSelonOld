<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cart extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /* load libraries */
        $this->load->library(
            array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Master', 'uuid', 'Notification')
        );

        /* load models */
        $this->load->model('checkout_model', 'checkout');
        $this->load->model('cart_model', 'cart');

        /* default breadcrumb */
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        /* define varibel for customer id from session */
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    public function viewCartSession()
    {
        //        $this->session->userdata['sess_cart'] = [];
        echo '<pre>';
        print_r($this->session->userdata('sess_cart'));
    }

    public function addToCart()
    {

        if (is_null($this->session->userdata('logged_in'))) {
            $productId = $this->input->post('productId');
            $productQty = $this->cart->productQty($productId);

            $qty = $this->input->post('qty'); // add to cart quantity

            if (is_null($this->session->userdata('sess_cart'))) {
                $this->session->set_userdata('sess_cart');
            }

            $sess = $this->session->userdata['sess_cart'];
            $cart_sess = [];

            $this->db->select('(price * (priceMargin/100)) as totalMargin')
                ->select('(price * (discount/100)) as totalDiscount')
                ->select('(price - (price * (discount/100))) as priceAfterDiscount')
                ->select('(price + (price * (priceMargin/100))) as priceAfterMargin')
                ->select('floor(price - (price * discount/100) + ((price - (price * discount/100)) * priceMargin / 100)) as totalPrice, images, name');
            $data = $this->db->get_where('Product', ['id' => $productId])->row();
            //print_r($this->db->last_query());die;

            $image = json_decode($data->images);
            if (@getimagesize(IMG_PRODUCT . $image[0]->thumbnail)) {
                $imageFix = IMG_PRODUCT . $image[0]->thumbnail;
            } else {
                $imageFix = base_url('assets/img/blog/man-1.jpg');
            }

            $url = base_url('Product/detail/' . url_title($data->name, '-', true) . '?id=' . $productId);
            $keyfix = null;

            if (count($sess) != 0) {
                foreach ($sess as $key => $s_cart) {
                    if ($s_cart['productId'] == $productId) {
                        $qtyBefore = (int) $s_cart['quantity'];
                        $this->session->userdata['sess_cart'][$key]['quantity'] = $qty + $qtyBefore;
                        $keyfix = $key;
                    }
                }

                if (!is_null($keyfix)) {
                    echo json_encode($this->session->userdata['sess_cart'][$keyfix]);
                } else {
                    $cart_sess = ['status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url];
                    array_push($this->session->userdata['sess_cart'], $cart_sess);
                    echo json_encode(['status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url]);
                }
            } else {
                $cart_sess[] = ['status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url];
                //                $this->session->sess_destroy();
                $this->session->set_userdata('sess_cart', $cart_sess);
                echo json_encode(['status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url]);
            }
        } else {
            //        $this->output->enable_profiler(TRUE);
            //print_r($_POST);die;
            $productId = $this->input->post('productId');
            $productQty = $this->cart->productQty($productId);

            /* load model to get previous cart */
            $check = $this->cart->check_item($productId, $this->customerId);
            $qty = $this->input->post('qty');

            $lebih = null;
            /* if cart product exist then add qty */
            if ($check != false) {
                $prevCart = $check->row();
                //            $prevQty = $prevCart->quantity;
                $lastQty = $prevCart->quantity + $qty;
                /* update qty */
                if ($lastQty > $productQty) {
                    $lebih = 'melebihi stok';
                } else {
                    $query = $this->db->update('Cart', array('quantity' => $lastQty), array('id' => $prevCart->id));
                }
                /* if product not exist in cart then add row */
            } else {
                $data = array(
                    'id' => $this->uuid->v4(),
                    'productId' => $productId,
                    'customerId' => $this->customerId,
                    'quantity' => $qty,
                    'note' => ''
                );
                $query = $this->db->insert('Cart', $data);
            }

            if ($lebih != 'melebihi stok') {

                if ($query) {
                    $this->db->select('(price * (priceMargin/100)) as totalMargin')
                        ->select('(price * (discount/100)) as totalDiscount')
                        ->select('(price - (price * (discount/100))) as priceAfterDiscount')
                        ->select('(price + (price * (priceMargin/100))) as priceAfterMargin')
                        ->select('(price - (price * (discount/100))) + ((price - (price * (discount/100))) * (priceMargin/100) ) as totalPrice')
                        ->select('Cart.quantity as quantityCart, images, name')
                        ->join('Product', 'Product.id=Cart.productId', 'left')->where(['customerId' => $this->customerId, 'productId' => $productId]);
                    $data = $this->db->get('Cart')->row();
                    //print_r($this->db->last_query());die;

                    $image = json_decode($data->images);
                    if (@getimagesize(IMG_PRODUCT . $image[0]->thumbnail)) {
                        $imageFix = IMG_PRODUCT . $image[0]->thumbnail;
                    } else {
                        $imageFix = base_url('assets/img/blog/man-1.jpg');
                    }

                    $url = base_url('Product/detail/' . url_title($data->name, '-', true) . '?id=' . $productId);

                    echo json_encode(['status' => 'success', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $data->quantityCart, 'image' => $imageFix, 'name' => $data->name, 'url' => $url]);

                        /* echo '<pre>';
                      print_r($data) */;
                } else {
                    echo json_encode(['status' => 'failed']);
                }
            } elseif ($lebih == 'melebihi stok') {
                echo json_encode(['status' => 'overload']);
            }
        }
    }

    public function detail($id)
    {
        $this->checkLogin();
        $data['products'] = $this->cart_model->product_details($id);
        $this->kerangka->site('detail', $data);
    }

    public function delete($id)
    {
        $this->checkLogin();
        $delete = $this->cart->deleteCart($id);
        if ($delete == 1) {
            echo json_encode(['message' => 'sukses']);
        } else {
            echo json_encode(['message' => 'gagal']);
        }
    }

    public function delete_checkout($id)
    {
        $delete = $this->cart->deleteCart($id);
        if ($delete == 1) {
            redirect($this->agent->referrer());
        } else {
            echo json_encode(['message' => 'gagal']);
        }
    }

    public function search_array_custom($id)
    {
        $sess_cart = $this->session->userdata('sess_cart');
        foreach ($sess_cart as $key => $product) {
            if ($product['productId'] === $id)
                return $key;
        }
        return 'tidak ada';
    }

    public function delete_sess($id)
    {
        $sess_cart = $this->session->userdata('sess_cart');
        $key = $this->search_array_custom($id);
        //        $delete = 0;
        //        echo $key;

        if (count($sess_cart) == 0) {
            echo json_encode(['message' => 'sukses']);
        } else {
            if ($key === 'tidak ada') {
                echo json_encode(['message' => 'gagal']);
            } else {
                //                echo $key;
                unset($_SESSION['sess_cart'][$key]);
                echo json_encode(['message' => 'sukses']);
                //                echo '<pre>';print_r($this->session->userdata('sess_cart'));
                //                redirect($this->agent->referrer());
            }
        }
    }

    public function riwayatTransaksi()
    {
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Riwayat Transaksi', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        /* all data cart by merchant */
        $data['customerOrder'] = $this->checkout->getCustomerInvoice($this->customerId, ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']);
        $data['customerId'] = $this->customerId;

        $data['page'] = 'pembayaran';
        /* load view */
        $this->template->load($data, 'history', 'history', 'Cart');
    }

    public function ajax_list_pembayaran()
    {
        $this->checkLogin();
        $list = $this->cart->get_data_pembayaran();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $menu) {
            $no++;
            $row = array();
            $row[] = $menu->nama_menu;
            $row[] = $menu->link;
            $row[] = $menu->icon;
            $row[] = $menu->count;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="edit_menu(' . "'" . $menu->id_menu . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_menu(' . "'" . $menu->id_menu . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->menu->count_all(),
            "recordsFiltered" => $this->menu->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function pembayaran()
    {
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Riwayat Transaksi', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /* all data cart by merchant */
        //        $data['customerOrder'] = $this->checkout->getCustomerInvoice($this->customerId, ['1', '2', '3']);
        $data['customerId'] = $this->customerId;

        $data['page'] = 'pembayaran';
        $data['title'] = 'Status Pemesanan Transfer - Halal Shopping';
        //        echo '<pre>';print_r($data['customerOrder']);die;
        /* load view */
        $this->template->load($data, 'pemesanan', 'pemesanan', 'Cart');
    }

    public function invoice()
    {
        $this->checkLogin();
        $invoiceId = $this->input->get('invoiceId');
        $data['customerOrder'] = $this->checkout->getCustomerInvoiceById($invoiceId);
        $data['page'] = 'invoice';
        //        echo '<pre>';
        //        print_r($data['customerOrder']);
        $this->load->view('invoice', $data);
    }

    public function pesanan_gagal()
    {
        $this->checkLogin();
        $this->breadcrumbs->push('Order Sedang Dikirim', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['allRejected'] = $this->checkout->rejectedInvoice($this->customerId);

        $data['page'] = 'pesanan_gagal';
        $data['title'] = 'Pesanan Gagal - Halal Shopping';
        $this->template->load($data, 'pesanan_gagal', 'pesanan_gagal', 'Cart');
    }

    public function pengiriman()
    {
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Order Sedang Dikirim', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['allDeliveredOrder'] = $this->checkout->getOrder($this->customerId, [3, 4, 5]);


        //echo '<pre>';print_r($data['allDeliveredOrder']);die;
        //        echo '<pre>';
        //        echo print_r($data['allDeliveredOrder']);
        //        exit;

        $data['page'] = 'pengiriman';
        $data['title'] = 'Pesanan dikirim - Halal Shopping';
        $this->template->load($data, 'pengiriman', 'pengiriman', 'Cart');
    }

    public function barangDiterima()
    {
        $this->output->enable_profiler(false);
        $this->checkLogin();
        $idOrder = $this->input->get('order');

        $query = $this->db->update('Order', array('status' => 7), array('id' => $idOrder));
        /*notification*/
        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $this->customerId, 'contentId' => $idOrder, 'contentType' => 'Order', 'contentLink' => base_url() . 'Pesan/Ulasan', 'contentImage' => 'fa fa-comment', 'content' => 'Anda Punya Transaksi yang belum diulas'));

        //        echo json_encode(array('status' => 'failed'));

        if ($query) {
            echo '<meta http-equiv="refresh" content="0;URL=\'' . base_url() . 'Pesan/ulasan#' . $idOrder . '\'" />';
            //echo '<script>parent.window.location.assign("'.base_url().'Pesan/ulasan#'+$idOrder+'")</script>';
        } else {
            echo json_encode(array('status' => 'failed'));
        }
    }

    //    public function bankName() {
    //        $list = $this->Section_model->bankNameOnlyValue() ;
    //        echo json_encode($list);
    //    }

    public function unggah_bukti()
    {
        $this->checkLogin();
        $data['invoiceId'] = $this->input->get('invoiceId');
        $params = [
            'link' => API_DATA . 'api/escrow/accounts?lastUpdated',
            'data' => array()
        ];

        $data['title'] = 'Unggah Bukti Transfer - Halal Shopping';
        $data['escrow_list'] = $this->api->getApiData($params);
        $data['customerOrder'] = $this->checkout->getCustomerInvoiceByIdApi($this->customerId, $data['invoiceId']);
        //echo '<pre>';print_r($data['customerOrder']);die;
        $this->template->load($data, 'unggah_bukti', 'unggah_bukti', 'Cart');
    }

    public function process_unggah_bukti()
    {
        $invoiceId = $this->input->get('invoiceId');
        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $this->customerId, 'contentId' => $invoiceId, 'contentType' => 'Invoice', 'contentLink' => base_url() . 'Cart/invoice?invoiceId=' . $invoiceId . '', 'contentImage' => 'fa fa-money', 'content' => 'Transaksi anda telah berhasil dilakukan (' . $invoiceId . ')'));

        $order = $this->db->get_where('Order', array('invoiceId' => $invoiceId))->result();
        foreach ($order as $key_order => $value_order) {
            # code...
            $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $value_order->merchantId, 'contentId' => $value_order->id, 'contentType' => 'Order', 'contentLink' => '', 'contentImage' => 'fa fa-money', 'content' => 'Anda Punya 1 Transaksi Baru dari ' . $this->session->userdata('user')->customerName . ''));
        }

        return false;
    }


    public function UpdateCart()
    {
        $this->checkLogin();
        $this->db->update('Cart', array('quantity' => $this->input->post('qty')), array('id' => $this->input->post('ID')));
    }

    public function delete_address($id)
    {
        $this->checkLogin();
        $this->db->delete('CustomerAddress', array('id' => $id));
        echo json_encode(array("status" => TRUE));
    }

    public function opencomplaint($error = null)
    {
        $this->checkLogin();
        $data = array();
        $data['title'] = 'Unggah Bukti Transfer - Halal Shopping';
        $data['orderId'] = $this->input->get('order');
        $data['productId'] = $this->input->get('product');
        $data['merchantId'] = $this->input->get('merchant');

        $product = $this->db->query('select `name`, `condition` from Product where id = "' . $data['productId'] . '"')->row_array();
        $merchant = $this->db->query('select `name` from Merchant where id = "' . $data['merchantId'] . '"')->row_array();
        $order = $this->db->query('select `id`, `invoiceId` from `Order` where id = "' . $data['orderId'] . '"')->row_array();

        $data['product'] = $product;
        $data['merchant'] = $merchant;
        $data['order'] = $order;
        $data['error'] = $error;

        $this->load->view('complaint_view', $data);
    }

    public function prosescomplaint()
    {
        $this->checkLogin();
        $subject = $this->input->post('subject');
        $msg = $this->input->post('message');
        $orderId = $this->input->get('order');
        $productId = $this->input->get('product');
        $merchantId = $this->input->get('merchant');

        $cart = new Cart_model();
        $ress = $cart->complaintProses($orderId, $productId, $merchantId, $subject, $msg);

        if (isset($ress->error)) {
            $this->opencomplaint($ress->error->message);
        } else {
            echo '<script>parent.document.location.href = "' . base_url() . 'Pesan/Komplain";</script>';
        }
    }

    public function confcomplaint()
    {
        $this->checkLogin();

        $data = array();
        $data['message'] = $this->input->post('message');
        $data['complaintId'] = $this->input->post('Id');
        $data['modifiedBy'] = $this->input->post('By');
        $data['modifiedByRole'] = $this->input->post('ByRole');

        $this->db->set('id', 'UUID()', FALSE);
        $ress = $this->db->insert('ComplaintConversation', $data);

        echo '<meta http-equiv="refresh" content="0;URL=\'' . base_url() . 'Pesan/Komplain/detail?tiket=' . $data['complaintId'] . '#balas-pesan\'" />';
    }

    public function openconfirm($error = null)
    {



        $data = array();
        $data['title'] = 'Form Konfirmasi Penerimaan Barang - Halal Shopping';
        $data['orderId'] = $this->input->get('order');
        $data['merchantId'] = $this->input->get('merchant');

        $merchant = $this->db->query('select `name` from Merchant where id = "' . $data['merchantId'] . '"')->row_array();
        $order = $this->db->query('select `id`, `invoiceId` from `Order` where id = "' . $data['orderId'] . '"')->row_array();
        $product = $this->db->query('select a.productId, a.quantity, a.subtotalDiscount, a.notes, b.*, c.name as name_category from OrderDetail a left join Product b ON (a.productId = b.id) left join ProductCategory c ON (b.productCategoryId = c.id)   where orderId = "' . $data['orderId'] . '"')->result();

        $data['merchant'] = $merchant;
        $data['order'] = $order;
        $data['error'] = $error;
        $data['product'] = $product;

        $this->load->view('confirm_view', $data);
    }

    public function complaintlist()
    {
    }

    private function checkLogin()
    {
        /* cek session logged in */
        if (!($this->session->userdata('logged_in'))) {
            echo '<meta http-equiv="refresh" content="0;URL=\'' . base_url() . 'Login' . '\'" />';
            echo '<script>parent.window.location.assign("' . base_url() . 'Login")</script>';
        }
    }
}
