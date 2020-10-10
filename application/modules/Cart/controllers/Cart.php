<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cart extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /* load libraries */
        $this->load->library(
            array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Master', 'uuid', 'Notification', 'pagination')
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
                    $cart_sess = ['session' => 'no', 'status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url];
                    array_push($this->session->userdata['sess_cart'], $cart_sess);
                    echo json_encode(['session' => 'no', 'status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url]);
                }
            } else {
                $cart_sess[] = ['session' => 'no', 'status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url];
                //                $this->session->sess_destroy();
                $this->session->set_userdata('sess_cart', $cart_sess);
                echo json_encode(['session' => 'no', 'status' => 'success_sess', 'productId' => $productId, 'priceTotal' => ceil($data->totalPrice), 'quantity' => $qty, 'image' => $imageFix, 'name' => $data->name, 'url' => $url]);
            }
        } else {
            $data = [
                'productId' => $this->input->post('productId'),
                'quantity' => $this->input->post('qty'),
                'notes' => $this->input->post('notes')
            ];
            $params = [
                'link' => API_DATA . 'Customers/' . $this->customerId . '/cart?access_token=' . $this->session->userdata('token'),
                'data' => $data
            ];

            $api = $this->api->getApiData($params);

            echo json_encode(['session' => 'yes', 'result_api' => $api]);
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

    public function transaksiBerhasil()
    {
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Riwayat Transaksi', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        /* all data cart by merchant */
        $data['allDeliveredOrder'] = $this->checkout->getDeliveredOrder($this->customerId, [7]);
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
        $ress_api = $this->checkout->getCustomerOrderList($this->customerId, ['1', '2', '3']);
        /*echo '<pre>';print_r($customerData);die;*/
        $customerData = isset($ress_api->error) ? $ress_api->error : $ress_api->result;
        $data['customerOrder'] = $customerData;
        /*echo '<pre>';print_r($data);die;*/
        //$data['customerOrder'] = $this->checkout->getCustomerInvoiceByApi($this->customerId);
        $data['customerId'] = $this->customerId;

        $new_array = array();
        foreach ($data['customerOrder'] as $key => $value) {
            if (isset($value->notificationId)) {
                if (is_null($value->notificationId) === false) {
                    $new_array[$key] = $value;
                }
            }
        }
        //        echo '<pre>'; print_r($data['customerOrder']);die;
        $data['countNotif'] = count($new_array);

        $data['page'] = 'pembayaran';
        $data['title'] = 'Status Pemesanan Transfer - Halal Shopping';
        /* load view */
        $this->template->load($data, 'pemesanan', 'pemesanan', 'Cart');
    }

    public function invoice()
    {
        $this->checkLogin();
        $invoiceId = $this->input->get('invoiceId');
        $data['customerOrder'] = $this->checkout->getCustomerInvoiceById($invoiceId)->result;
        $data['invoiceLog'] = $this->checkout->getInvoiceLog($invoiceId);
        $data['page'] = 'invoice';
        //echo '<pre>';print_r($data);die;
        /*delete notification*/
        $ntf = $this->input->get('ntf');
        if (isset($ntf)) {
            $this->db->delete('UserNotification', ['id' => $ntf]);
        }
        $this->load->view('invoice', $data);
    }

    public function detail_invoice()
    {
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Riwayat Transaksi', get_class($this) . '/pembayaran');
        $this->breadcrumbs->push('Transaksi dibatalkan', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $invoiceId = $this->input->get('invoiceId');
        $data['customerOrder'] = $this->checkout->getCustomerInvoiceById($invoiceId);
        $data['page'] = 'invoice';
        $data['customerId'] = $this->customerId;
        $data['invoiceId'] = $invoiceId;

        //        echo '<pre>';
        //        print_r($data['customerOrder']);
        $this->template->load($data, 'detail_invoice', 'detail_invoice', 'Cart');
    }
    public function transaksi_dibatalkan()
    {
        $this->checkLogin();
        $this->breadcrumbs->push('Order Sedang Dikirim', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        //$data['allRejected'] = $this->checkout->rejectedInvoice($this->customerId);
        $data['allRejected'] = $this->checkout->getDeliveredOrder($this->customerId, [8]);;

        //echo '<pre>';print_r($data['allRejected']);die;
        $data['page'] = 'transaksi_dibatalkan';
        $data['title'] = 'Pesanan Dibatalkan - Halal Shopping';
        $this->template->load($data, 'pesanan_batal', 'pesanan_batal', 'Cart');
    }

    public function pengiriman()
    {
        //print_r($this->session->userdata('token'));die;
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Order Sedang Dikirim', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();


        $data['allDeliveredOrder'] = $this->checkout->getDeliveredOrder($this->customerId, [5, 6]);

        $data['page'] = 'pengiriman';
        $data["links"] = $this->pagination->create_links();
        $data['title'] = 'Pesanan dikirim - Halal Shopping';

        //        print_r($config["total_rows"]);
        $this->template->load($data, 'pengiriman', 'pengiriman', 'Cart');
    }

    public function barangDiterima()
    {
        //print_r($_POST);die;
        $this->output->enable_profiler(false);
        $this->checkLogin();
        $idOrder = $this->input->post('orderId');

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

    public function transaksi_ditolak()
    {
        $this->checkLogin();
        $this->breadcrumbs->push('Order Sedang Dikirim', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        //$data['allRejected'] = $this->checkout->rejectedInvoice($this->customerId);
        $data['allRejected'] = $this->checkout->getCustomerInvoice($this->customerId, ['4']);;
        //echo '<pre>';print_r($data['allRejected']);die;
        $data['page'] = 'transaksi_ditolak';
        $data['title'] = 'Pesanan Ditolak - Halal Shopping';
        $this->template->load($data, 'pesanan_gagal', 'pesanan_gagal', 'Cart');
    }

    //    public function bankName() {
    //        $list = $this->Section_model->bankNameOnlyValue() ;
    //        echo json_encode($list);
    //    }

    public function unggah_bukti()
    {
        $this->checkLogin();
        $data['invoiceId'] = $this->input->get('invoiceId');

        /*delete notification*/
        $ntf = $this->input->get('ntf');
        if (isset($ntf)) {
            $this->db->delete('UserNotification', ['id' => $ntf]);
        }

        $params = [
            'link' => API_DATA . 'escrow/accounts?lastUpdated',
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

        /*delete old notification*/
        $this->db->delete('UserNotification', ['userId' => $this->customerId, 'contentId' => $invoiceId, 'contentType' => 'invoice']);

        /*add notification*/
        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $this->customerId, 'contentId' => $invoiceId, 'contentType' => 'Invoice', 'contentLink' => 'Cart/invoice?invoiceId=' . $invoiceId . '', 'contentImage' => 'fa fa-money', 'content' => 'Transaksi anda telah berhasil dilakukan (' . $invoiceId . ')'));

        //Add notification to admin
        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => 1, 'contentId' => $invoiceId, 'contentType' => 'Invoice', 'contentLink' => '', 'contentImage' => 'fa fa-money', 'content' => 'Transaksi ' . $invoiceId . ' telah dibayar, lakukan verfikasi secepatnya'));

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

    public function getDetailOrderPemesanan()
    {
        $orderId = $this->input->get('orderId');
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/order?access_token=' . $this->session->userdata('token') . '&orderId=' . $orderId . '&lastUpdated',
            'data' => array()
        ];

        $api = $this->api->getApiData($params);
        $customerOrder = $api->result;
        /*echo '<pre>';print_r($customerOrder);die;*/
        $statusOrder = $this->db->get('OrderStatus')->result_array();

        $btn = '';
        $html = '';
        $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
        $btn .= '<a class="btn btn-info" title="Lihat invoice" href="' . base_url() . 'Cart/invoice?invoiceId=' . $customerOrder->invoiceId . '" target="_blank"> <i class="fa fa-file"></i> Lihat Invoice </a> ';
        //        if($customerOrder->status == 1) {
        //            if($customerOrder->method == 0 || $customerOrder->method == 3) {
        //                $btn.= '<a class="btn btn-danger" title="Lanjutkan pembayaran" href="'.base_url().'Cart/Checkout/payment_method?invoiceId='.$customerOrder->invoiceId.'"><i class="fa fa-money"></i> Lanjutkan Proses</a> ';
        //            }elseif($customerOrder->method == 1){
        //                $btn.= '<a class="btn btn-success" title="Unggah bukti pembayaran" href="'.base_url().'Cart/unggah_bukti?invoiceId='.$customerOrder->invoiceId.'"><i class="fa fa-upload"></i> Unggah Bukti</a>';
        //            }elseif ($customerOrder->method == 2) {
        //                $getUrl = $this->checkout->getRedirectUrlFromFinpaydev($customerOrder->invoiceId);
        //                $btn.= '<a class="btn btn-danger" title="Pembayaran Kartu Kredit" href="'.str_replace('"','',$getUrl->url).'" target="blank"><i class="fa fa-credit-card"></i> Lanjutkan Pembayaran </a>';
        //            }
        //        }

        $html .= '<tr>
                    <td colspan="9" align="right">
                    ' . $btn . '
                    </td>
                </tr>';

        $key = array_search($customerOrder->status, array_column($statusOrder, 'id'));
        $merchant = $customerOrder->merchant;
        $notes = $customerOrder->notes;
        /*isset rejected*/
        $reject = isset($notes->reject) ? $notes->reject->items : array();
        if ($customerOrder->status == 4) {
            $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan Sedang diproses Oleh Merchant</span> </td>';
        } elseif ($customerOrder->status == 2) {
            $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Menunggu Verifikasi Pembayaran</span> </td>';
        } elseif ($customerOrder->status == 3) {
            $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-success" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' -Pembayaran Sudah diverifikasi, Pesanan diteruskan ke Merchant</span> </td>';
        } elseif ($customerOrder->status == 5) {
            if ($merchant->type == 2) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-success" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan Sudah Berada di Gedung TSO (Silahkan diambil)</span> </td>';
            } else {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-success" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan Sedang Dalam Proses Pengiriman</span> </td>';
            }
        } elseif ($customerOrder->status == 7) {
            if ($merchant->type == 2) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan telah selesai Anda terima</span> </td>';
            } else {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan telah selesai Anda terima</span> </td>';
            }
        } elseif ($customerOrder->status == 8) {
            $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-danger" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Rejected By Merchant</span> </td>';
        } elseif ($customerOrder->status == 6) {
            if ($merchant->type == 2) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Silahkan ambil di gedung TSO</span> </td>';
            } else {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . '</span> </td>';
            }
        } elseif ($customerOrder->status == 11) {
            $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-warning" style="font-size: 100%;">' . $statusOrder[$key]['name'] . '</span> </td>';
        } else {
            $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . '</span> </td>';
        }


        /*if($rowOrder->status == 4) {
            $html .= '<tr>
                    <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-success" style="font-size: 100%;">Pesanan diproses merchant</span> </td>';
        }elseif($rowOrder->status == 5) {
            $html .= '<tr>
                    <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-warning" style="font-size: 100%;">Pesanan telah dikirim merchant</span> </td>';
        }elseif($rowOrder->status == 8) {
            $html .= '<tr>
                    <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-danger" style="font-size: 100%;">Pesanan ditolak merchant</span> </td>';
        }else{
            $html .= '<tr>
                    <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-default" style="font-size: 100%;">Pesanan belum diproses merchant</span> </td>';
        }*/

        $html .= '<td colspan="3" align="right"><b>' . $customerOrder->id . '</b> </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>Image</td>
                            <td>Nama Produk</td>
                            <td>Notes</td>
                            <td style="width:100px" align="center">Qty</td>
                            <td style="width:100px" align="center">Berat</td>
                            <td style="width:100px" align="right">Harga Satuan</td>
                            <td style="width:100px" align="right">Subtotal</td>
                        </tr>
                    <tbody>';

        foreach ($customerOrder->items as $rowOd) {
            //print_r($rowOd);
            $product = $rowOd->product;
            /*$priceNett = $rowOd->subtotalPrice + $rowOd->subtotalMargin - $rowOd->subtotalDiscount;
            $subTotal[] = $priceNett;
            $subTotalWeight[] = $rowOd->subtotalWeight;*/
            $netTotal[] = $rowOd->netTotal;

            /*array search reject*/
            $key = array_search($product->id, array_column($reject, 'productId'));
            $show_reason_reject = isset($reject[$key]->reason) ? '' . $reject[$key]->reason : '';

            /*komplain*/
            $komplain = $this->cart->getComplaintByOrder($customerOrder->id, $product->id);

            $komplain_status = ($komplain->num_rows() > 0) ? ($komplain->row()->status == 3) ? '<span style="color:green"> <i class="fa fa-check"></i> ' . $komplain->row()->name . '</span>' : '( ' . $komplain->row()->name . ' )' : '';

            $subject_komplain = ($komplain->num_rows() > 0) ? '' . $komplain->row()->subject . '<br>' . $komplain_status . ' ' : '';

            $icon = '';

            if ($product->id == isset($reject[$key]) ? $reject[$key]->productId : 0) {
                $icon = '<span style="color:red"><i class="fa fa-times"></i></span>';
            } else {
                if ($customerOrder->status == 4 || $customerOrder->status == 5) {

                    $icon = '<span style="color:greenyellow"><i class="fa fa-check"></i></span>';
                } else {
                    $icon = '<span style="color:greenyellow"><i class="fa fa-check"></i></span>';
                }
            }


            if ($rowOd->status == 2) {
                $html .= '<tr>
                        <td align="center"><span style="color:red"><i class="fa fa-times"></i></span></td>
                        <td align="left"><img src="' . IMG_PRODUCT . $product->images[0]->thumbnail . '" width="80px"></td>
                        <td>' . $product->name . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="center">' . $rowOd->quantity . '</td>
                        <td align="center">' . $rowOd->subtotalWeight . ' Gram</td>
                        <td align="right">' . 'Rp ' . number_format($product->netTotal) . '</td>
                        <td align="right">' . 'Rp ' . number_format($rowOd->netTotal) . '</td>
                    </tr>';
            } else {
                $html .= '<tr>
                        <td align="center">' . $icon . '</td>
                        <td align="left"><img src="' . IMG_PRODUCT . $product->images[0]->thumbnail . '" width="80px"></td>
                        <td>' . $product->name . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="center">' . $rowOd->quantity . '</td>
                        <td align="center">' . $rowOd->subtotalWeight . ' Gram</td>
                        <td align="right">' . 'Rp ' . number_format($product->netTotal) . '</td>
                        <td align="right">' . 'Rp ' . number_format($rowOd->netTotal) . '</td>
                    </tr>';
            }

            $alasanRejected = $this->checkout->getReason($customerOrder->id);
            $alasan2 = '';
            foreach ($alasanRejected as $rejected) {
                $alasan2 .= '<span style="color: red;">' . $rejected->remark . '</span><br>';
            }
            if ($rowOd->status == 2) {
                $html .= '<tr>
<td colspan="8">Alasan reject:<br>' . $alasan2 . '</td>
</tr>';
            }
        }

        //        $response = json_decode($rowOrder->response);
        //                $rand = isset($response->uniqueCode)?$response->uniqueCode:0;
        //$total = $customerOrder->netTotal + $rand + $customerOrder->totalShippingCost;

        $customerUniqueCode = isset($customerOrder->uniqueCode) ? $customerOrder->uniqueCode : '';
        $netTotalAll = $customerOrder->netTotal + $customerUniqueCode;

        $html .= '<tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="7">
                        <b>Subtotal</b>
                    </td>
                    <td align="right">' . 'Rp ' . number_format(array_sum($netTotal)) . '</td>
                </tr>
                <tr>
                    <td colspan="6"><b>Biaya Pengiriman</b></td>
                    <td align="right">' . $customerOrder->totalWeight . ' Gram</td>
                    <td align="right">' . 'Rp ' . number_format($customerOrder->totalShippingCost) . '</td>
                </tr>
                <tr>
                    <td colspan="7"><b>Kode Unik Pembayaran</b></td>
                    <td align="right">' . 'Rp ' . number_format(isset($customerOrder->uniqueCode) ? $customerOrder->uniqueCode : 0) . '</td>
                </tr>
                <tr>
                    <td colspan="7"><b>Potongan Voucher</b></td>
                    <td align="right">' . 'Rp -' . number_format(isset($customerOrder->totalVoucher) ? $customerOrder->totalVoucher : 0) . '</td>
                </tr>
                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="7"><b>Total Pembayaran</b></td>
                    <td align="right">' . '<b>Rp ' . number_format($netTotalAll) . '</b>' . '</td>
                </tr>
                </tbody>
            </table>';

        //$result = $this->checkout->getCustomerInvoiceById($invoiceId);
        /*echo '<pre>';
        echo $html;*/

        echo json_encode(array('html' => $html));
    }

    public function getDetailOrder()
    {
        $invoiceId = $this->input->get('invoiceId');
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/invoice?access_token=' . $this->session->userdata('token') . '&invoiceId=' . $invoiceId . '&sort=status&sortItems=status',
            'data' => array()
        ];

        $api = $this->api->getApiData($params);
        $customerOrder = $api->result;
        //        echo '<pre>';print_r($customerOrder);die;
        $statusOrder = $this->db->get('OrderStatus')->result_array();

        $btn = '';
        $html = '';
        $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
        $btn .= '<a class="btn btn-info" title="Lihat invoice" href="' . base_url() . 'Cart/invoice?invoiceId=' . $customerOrder->id . '" target="_blank"> <i class="fa fa-file"></i> Lihat Invoice </a> ';
        if ($customerOrder->status == 1) {
            if ($customerOrder->method == 0 || $customerOrder->method == 3) {
                $btn .= '<a class="btn btn-danger" title="Lanjutkan pembayaran" href="' . base_url() . 'Cart/Checkout/payment_method?invoiceId=' . $customerOrder->id . '"><i class="fa fa-money"></i> Lanjutkan Proses</a> ';
            } elseif ($customerOrder->method == 1) {
                $btn .= '<a class="btn btn-success" title="Unggah bukti pembayaran" href="' . base_url() . 'Cart/unggah_bukti?invoiceId=' . $customerOrder->id . '"><i class="fa fa-upload"></i> Unggah Bukti</a>';
            } elseif ($customerOrder->method == 2) {
                $getUrl = $this->checkout->getRedirectUrlFromFinpaydev($invoiceId);
                $btn .= '<a class="btn btn-danger" title="Pembayaran Kartu Kredit" href="' . str_replace('"', '', $getUrl->url) . '" target="blank"><i class="fa fa-credit-card"></i> Lanjutkan Pembayaran </a>';
            }
        }

        $html .= '<tr>
                    <td colspan="9" align="right">
                    ' . $btn . '
                    </td>
                </tr>';

        foreach ($customerOrder->orders as $rowOrder) {
            $key = array_search($rowOrder->status, array_column($statusOrder, 'id'));
            $merchant = $rowOrder->merchant;
            $notes = $rowOrder->notes;
            /*isset rejected*/
            $reject = isset($notes->reject) ? $notes->reject->items : array();
            if ($rowOrder->status == 4) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan Sedang diproses Oleh Merchant</span> </td>';
            } elseif ($rowOrder->status == 2) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Menunggu Verifikasi Pembayaran</span> </td>';
            } elseif ($rowOrder->status == 3) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-success" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' -Pembayaran Sudah diverifikasi, Pesanan diteruskan ke Merchant</span> </td>';
            } elseif ($rowOrder->status == 5) {
                if ($merchant->type == 2) {
                    $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-success" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan Sudah Berada di Gedung TSO (Silahkan diambil)</span> </td>';
                } else {
                    $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-success" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan Sedang Dalam Proses Pengiriman</span> </td>';
                }
            } elseif ($rowOrder->status == 7) {
                if ($merchant->type == 2) {
                    $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan telah selesai Anda terima</span> </td>';
                } else {
                    $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Pesanan telah selesai Anda terima</span> </td>';
                }
            } elseif ($rowOrder->status == 8) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-danger" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Rejected By Merchant</span> </td>';
            } elseif ($rowOrder->status == 6) {
                if ($merchant->type == 2) {
                    $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . ' - Silahkan ambil di gedung TSO</span> </td>';
                } else {
                    $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . '</span> </td>';
                }
            } elseif ($rowOrder->status == 11) {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-warning" style="font-size: 100%;">' . $statusOrder[$key]['name'] . '</span> </td>';
            } else {
                $html .= '<tr><td colspan="6"><b>' . strtoupper($merchant->name) . '</b> <span class="label label-info" style="font-size: 100%;">' . $statusOrder[$key]['name'] . '</span> </td>';
            }


            /*if($rowOrder->status == 4) {
                        $html .= '<tr>
                                <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-success" style="font-size: 100%;">Pesanan diproses merchant</span> </td>';
                    }elseif($rowOrder->status == 5) {
                        $html .= '<tr>
                                <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-warning" style="font-size: 100%;">Pesanan telah dikirim merchant</span> </td>';
                    }elseif($rowOrder->status == 8) {
                        $html .= '<tr>
                                <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-danger" style="font-size: 100%;">Pesanan ditolak merchant</span> </td>';
                    }else{
                        $html .= '<tr>
                                <td colspan="6"><b>'.strtoupper($merchant->name).'</b> <span class="label label-default" style="font-size: 100%;">Pesanan belum diproses merchant</span> </td>';
                    }*/

            $html .= '<td colspan="3" align="right"><b>' . $rowOrder->id . '</b> </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>Image</td>
                            <td>Nama Produk</td>
                            <td>Notes</td>
                            <td style="width:100px" align="center">Qty</td>
                            <td style="width:100px" align="center">Berat</td>
                            <td style="width:100px" align="right">Harga Satuan</td>
                            <td style="width:100px" align="right">Subtotal</td>
                        </tr>
                    <tbody>';

            foreach ($rowOrder->items as $rowOd) {
                //print_r($rowOd);
                $product = $rowOd->product;
                /*$priceNett = $rowOd->subtotalPrice + $rowOd->subtotalMargin - $rowOd->subtotalDiscount;
                        $subTotal[] = $priceNett;
                        $subTotalWeight[] = $rowOd->subtotalWeight;*/
                $netTotal[] = $rowOd->netTotal;

                /*array search reject*/
                $key = array_search($product->id, array_column($reject, 'productId'));
                $show_reason_reject = isset($reject[$key]->reason) ? '' . $reject[$key]->reason : '';

                /*komplain*/
                $komplain = $this->cart->getComplaintByOrder($rowOrder->id, $product->id);

                $komplain_status = ($komplain->num_rows() > 0) ? ($komplain->row()->status == 3) ? '<span style="color:green"> <i class="fa fa-check"></i> ' . $komplain->row()->name . '</span>' : '( ' . $komplain->row()->name . ' )' : '';

                $subject_komplain = ($komplain->num_rows() > 0) ? '' . $komplain->row()->subject . '<br>' . $komplain_status . ' ' : '';

                $icon = '';

                if ($product->id == isset($reject[$key]) ? $reject[$key]->productId : 0) {
                    $icon = '<span style="color:red"><i class="fa fa-times"></i></span>';
                } else {
                    if ($rowOrder->status == 4 || $rowOrder->status == 5) {

                        $icon = '<span style="color:greenyellow"><i class="fa fa-check"></i></span>';
                    } else {
                        $icon = '<span style="color:greenyellow"><i class="fa fa-check"></i></span>';
                    }
                }


                if ($rowOd->status == 2) {
                    $html .= '<tr>
                        <td align="center"><span style="color:red"><i class="fa fa-times"></i></span></td>
                        <td align="left"><img src="' . IMG_PRODUCT . $product->images[0]->thumbnail . '" width="80px"></td>
                        <td>' . $product->name . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="center">' . $rowOd->quantity . '</td>
                        <td align="center">' . $rowOd->subtotalWeight . ' Gram</td>
                        <td align="right">' . 'Rp ' . number_format($product->netTotal) . '</td>
                        <td align="right">' . 'Rp ' . number_format($rowOd->netTotal) . '</td>
                    </tr>';
                } else {
                    $html .= '<tr>
                        <td align="center">' . $icon . '</td>
                        <td align="left"><img src="' . IMG_PRODUCT . $product->images[0]->thumbnail . '" width="80px"></td>
                        <td>' . $product->name . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="center">' . $rowOd->quantity . '</td>
                        <td align="center">' . $rowOd->subtotalWeight . ' Gram</td>
                        <td align="right">' . 'Rp ' . number_format($product->netTotal) . '</td>
                        <td align="right">' . 'Rp ' . number_format($rowOd->netTotal) . '</td>
                    </tr>';
                }

                $alasanRejected = $this->checkout->getReason($rowOrder->id);
                $alasan2 = '';
                foreach ($alasanRejected as $rejected) {
                    $alasan2 .= '<span style="color: red;">' . $rejected->remark . '</span><br>';
                }
                if ($rowOd->status == 2) {
                    $html .= '<tr>
<td colspan="8">Alasan reject:<br>' . $alasan2 . '</td>
</tr>';
                }
            }
        }

        //        $response = json_decode($rowOrder->response);
        //                $rand = isset($response->uniqueCode)?$response->uniqueCode:0;
        //$total = $customerOrder->netTotal + $rand + $customerOrder->totalShippingCost;

        $netTotalAll = $customerOrder->netTotal + $customerOrder->uniqueCode;

        $html .= '<tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="7">
                        <b>Subtotal</b>
                    </td>
                    <td align="right">' . 'Rp ' . number_format(array_sum($netTotal)) . '</td>
                </tr>
                <tr>
                    <td colspan="6"><b>Biaya Pengiriman</b></td>
                    <td align="right">' . $customerOrder->totalWeight . ' Gram</td>
                    <td align="right">' . 'Rp ' . number_format($customerOrder->totalShippingCost) . '</td>
                </tr>
                <tr>
                    <td colspan="7"><b>Kode Unik Pembayaran</b></td>
                    <td align="right">' . 'Rp ' . number_format(isset($customerOrder->uniqueCode) ? $customerOrder->uniqueCode : 0) . '</td>
                </tr>
                <tr>
                    <td colspan="7"><b>Potongan Voucher</b></td>
                    <td align="right">' . 'Rp -' . number_format($customerOrder->totalVoucher) . '</td>
                </tr>
                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="7"><b>Total Pembayaran</b></td>
                    <td align="right">' . '<b>Rp ' . number_format($netTotalAll) . '</b>' . '</td>
                </tr>
                </tbody>
            </table>';

        //$result = $this->checkout->getCustomerInvoiceById($invoiceId);
        /*echo '<pre>';
        echo $html;*/

        echo json_encode(array('html' => $html));
    }

    public function getDetailOrderByIdTransaksiBerhasil()
    {
        $orderId = $this->input->get('orderId');
        $status = $this->input->get('status') ? $this->input->get('status') : '';
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/order?access_token=' . $this->session->userdata('token') . '&orderId=' . $orderId,
            'data' => array()
        ];

        $no = 1;
        $api = $this->api->getApiData($params);
        //        echo '<pre>';print_r($api);
        $customerOrder = $api->result;
        $btn = '';
        $html = '';



        $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
        $btn .= '<a class="btn btn-info" title="Lihat invoice" href="' . base_url() . 'Cart/invoice?invoiceId=' . $customerOrder->invoiceId . '" target="_blank"> <i class="fa fa-file"></i> Lihat Invoice </a> ';
        $html .= '<input type="hidden" name="orderId" id="orderId" value="' . $customerOrder->id . '">';

        if (!in_array($status, array('success', 'canceled')) && $customerOrder->status != 6) {
            if (!in_array($customerOrder->status, array(11))) {
                $btn .= '<a id="submit_btn" class="btn btn-success btn-konfirm" data-id="' . $orderId . '"> <i class="fa fa-check"></i> Konfirmasi Penerimaan Barang  </a>';
            }
        }

        $html .= '<tr>
                    <td colspan="6" align="left">
                    <h3 style="float:left"><i class="fa fa-credit-card"></i> ' . $customerOrder->invoiceId . '</h3>
                    <div style="float:right">' . $btn . '</div>
                    </td>
                  </tr>';

        //foreach( $customerOrder as $rowOrder) :
        $subTotal = 0;
        $merchant = $customerOrder->merchant;
        $notes = $customerOrder->notes;
        /*isset rejected*/
        $reject = isset($notes->reject) ? $notes->reject->items : array();



        $html .= '<tr>
                        <td colspan="3"><b>' . strtoupper($merchant->name) . '</b></td>
                        <td colspan="3" align="right"><b>' . $customerOrder->id . '</b> </td>
                    </tr>';

        $html .= '<tr>
                        <td align="center" width="30px">No.</td>
                        <td style="max-width:80px">Images</td>
                        <td>Nama Produk</td>
                        <td>Harga Satuan</td>
                        <td>Catatan</td>
                        <td style="width:100px" align="center">Qty</td>
                    </tr>
                <tbody>';

        foreach ($customerOrder->items as $rowOd) :
            $product = $rowOd->product;
            $priceNett = $rowOd->subtotalPrice + $rowOd->subtotalMargin - $rowOd->subtotalDiscount;
            $subTotal += $priceNett;
            $subTotalWeight[] = $rowOd->subtotalWeight;

            /*array search reject*/
            //$key = array_search($product->id, array_column($reject, 'productId'));
            $key = $this->searchForKey($product->id, $reject, 'productId');

            $show_reason_reject = isset($reject[$key]->reason) ? '' . $reject[$key]->reason : 'Rejected by merchant';
            /*komplain*/
            $komplain = $this->cart->getComplaintByOrder($customerOrder->id, $product->id);

            $komplain_status = ($komplain->num_rows() > 0) ? ($komplain->row()->status == 3) ? '<span style="color:green"> <i class="fa fa-check"></i> ' . $komplain->row()->name . '</span>' : '( ' . $komplain->row()->name . ' )' : '';

            $subject_komplain = ($komplain->num_rows() > 0) ? '' . $komplain->row()->subject . '<br>' . $komplain_status . ' ' : '';

            $icon = ($show_reason_reject != '') ? '<span style="color:red"><i class="fa fa-times"></i></span>' : ($komplain->num_rows() > 0) ? '<span style="color:red"><i class="fa fa-times"></i></span>' : '<span style="color:green"><i class="fa fa-check"></i></span>';

            /*status order*/
            $status_order = $this->cart->getStatusOrder($rowOd->orderId);

            $checkUlasan = $this->checkout->checkUlasan($rowOd->orderId, $product->id);

            $complaintId = $this->checkout->complaintId($rowOd->orderId, $product->id);
            //                        $html = '<pre>'.print_r($complaintId->id).'</pre>';



            $html .= '<tr>
                        <td align="center">' . $no . '</td>
                        <td align="left"><img src="' . IMG_PRODUCT . $product->images[0]->thumbnail . '" width="80px"></td>
                        <td align="left" style="max-width: 150px;word-break: break-all">
                            ' . $product->id . '  ' . $product->name . '<br>
                            ' . $rowOd->subtotalWeight . ' Gram<br>
                        </td>
                        <td align="left">Rp ' . number_format($product->netTotal) . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="center">' . $rowOd->quantity . '</td>';

            $html .= '</tr>';

            $no++;

        endforeach;
        //endforeach;

        //                $rand = isset($response->uniqueCode)?$response->uniqueCode:0;
        //$total = $customerOrder->netTotal + $rand + $customerOrder->totalShippingCost;

        $html .= '<tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="5">
                        <b>Subtotal</b>
                    </td>
                    <td align="right">' . 'Rp ' . number_format($subTotal) . '</td>
                </tr>
                <tr>
                    <td colspan="4"><b>Biaya Pengiriman</b></td>
                    <td align="right">@' . $customerOrder->totalWeight . ' Gram</td>
                    <td align="right">' . 'Rp ' . number_format($customerOrder->totalShippingCost) . '</td>
                </tr>
                <tr>
                    <td colspan="5"><b>Total Pembayaran</b></td>
                    <td align="right">' . '<b>Rp ' . number_format($customerOrder->netTotal) . '</b>' . '</td>
                </tr>
                </tbody>
            </table>';

        echo json_encode(array('html' => $html));
    }

    public function getDetailOrderById()
    {
        $orderId = $this->input->get('orderId');
        $status = $this->input->get('status') ? $this->input->get('status') : '';
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/order?access_token=' . $this->session->userdata('token') . '&orderId=' . $orderId,
            'data' => array()
        ];

        $no = 1;
        $api = $this->api->getApiData($params);
        //        echo '<pre>';print_r($api);
        $customerOrder = $api->result;
        $btn = '';
        $html = '';

        /*===== jika status sukses atau menu transaksi berhasil======*/
        if ($status == 'success') {
            $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
            $address = $customerOrder->customerAddress;
            $courier = $customerOrder->courierCost;
            $html .= '<tr>
                    <td colspan="7" align="left"><h3><i class="fa fa-truck"></i> Riwayat Pengiriman</h3></td>
                  </tr>';
            $html .= '<tr>
                        <td>No Resi</td>
                        <td align="left">' . $customerOrder->shippingAirwaybill . ' <br> dikirim tanggal : - </td>
                        <td colspan="5">Biaya pengiriman : Rp. ' . number_format($customerOrder->totalShippingCost) . ' </td>
                      </tr>';

            $html .= '<tr>
                        <td>Penerima</td>
                        <td align="left"> ' . $address->recipientName . ' (' . $address->recipientPhone . ') <br> diterima tanggal : - </td>
                        <td colspan="5">Metode Pengiriman : ' . $courier->courierName . ' - ' . $courier->courierPackageName . '</td>
                      </tr>';

            $html .= '<tr><td>Alamat tujuan</td><td align="left" colspan="5">' . $address->address . '</td></tr>';
            $html .= '</table>';
        }



        $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
        $btn .= '<a class="btn btn-info" title="Lihat invoice" href="' . base_url() . 'Cart/invoice?invoiceId=' . $customerOrder->invoiceId . '" target="_blank"> <i class="fa fa-file"></i> Lihat Invoice </a> ';
        $html .= '<input type="hidden" name="orderId" id="orderId" value="' . $customerOrder->id . '">';

        if (!in_array($status, array('success', 'canceled')) && $customerOrder->status != 6) {
            if (!in_array($customerOrder->status, array(11))) {
                $btn .= '<a id="submit_btn" class="btn btn-success btn-konfirm" data-id="' . $orderId . '"> <i class="fa fa-check"></i> Konfirmasi Penerimaan Barang  </a>';
            }
        }

        $html .= '<tr>
                    <td colspan="7" align="left">
                    <h3 style="float:left"><i class="fa fa-credit-card"></i> ' . $customerOrder->invoiceId . '</h3>
                    <div style="float:right">' . $btn . '</div>
                    </td>
                  </tr>';

        //foreach( $customerOrder as $rowOrder) :
        $subTotal = 0;
        $merchant = $customerOrder->merchant;
        $notes = $customerOrder->notes;
        /*isset rejected*/
        $reject = isset($notes->reject) ? $notes->reject->items : array();



        $html .= '<tr>
                        <td colspan="4"><b>' . strtoupper($merchant->name) . '</b></td>
                        <td colspan="3" align="right"><b>' . $customerOrder->id . '</b> </td>
                    </tr>';

        $html .= '<tr>
                        <td align="center" width="30px">No.</td>
                        <td style="max-width:80px">Images</td>
                        <td>Nama Produk</td>
                        <td>Harga Satuan</td>
                        <td>Catatan</td>
                        <td style="width:100px" align="center">Qty</td>
                        <td align="center">Status Pesanan</td>
                    </tr>
                <tbody>';

        foreach ($customerOrder->items as $rowOd) :
            $product = $rowOd->product;
            $priceNett = $rowOd->subtotalPrice + $rowOd->subtotalMargin - $rowOd->subtotalDiscount;
            $subTotal += $priceNett;
            $subTotalWeight[] = $rowOd->subtotalWeight;

            /*array search reject*/
            //$key = array_search($product->id, array_column($reject, 'productId'));
            $key = $this->searchForKey($product->id, $reject, 'productId');

            $show_reason_reject = isset($reject[$key]->reason) ? '' . $reject[$key]->reason : 'Rejected by merchant';
            /*komplain*/
            $komplain = $this->cart->getComplaintByOrder($customerOrder->id, $product->id);

            $komplain_status = ($komplain->num_rows() > 0) ? ($komplain->row()->status == 3) ? '<span style="color:green"> <i class="fa fa-check"></i> ' . $komplain->row()->name . '</span>' : '( ' . $komplain->row()->name . ' )' : '';

            $subject_komplain = ($komplain->num_rows() > 0) ? '' . $komplain->row()->subject . '<br>' . $komplain_status . ' ' : '';

            $icon = ($show_reason_reject != '') ? '<span style="color:red"><i class="fa fa-times"></i></span>' : ($komplain->num_rows() > 0) ? '<span style="color:red"><i class="fa fa-times"></i></span>' : '<span style="color:green"><i class="fa fa-check"></i></span>';

            /*status order*/
            $status_order = $this->cart->getStatusOrder($rowOd->orderId);

            $checkUlasan = $this->checkout->checkUlasan($rowOd->orderId, $product->id);

            $complaintId = $this->checkout->complaintId($rowOd->orderId, $product->id);
            //                        $html = '<pre>'.print_r($complaintId->id).'</pre>';



            $html .= '<tr>
                        <td align="center">' . $no . '</td>
                        <td align="left"><img src="' . IMG_PRODUCT . $product->images[0]->thumbnail . '" width="80px"></td>
                        <td align="left" style="max-width: 150px;word-break: break-all">
                            ' . $product->id . '  ' . $product->name . '<br>
                            ' . $rowOd->subtotalWeight . ' Gram<br>
                        </td>
                        <td align="left">Rp ' . number_format($product->netTotal) . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="center">' . $rowOd->quantity . '</td>';

            if (count($checkUlasan) > 0) {

                $ratinghtml = '<div class="pro-rating" style="margin-top:-8px">';
                for ($i = 1; $i <= $checkUlasan->rating; $i++) {
                    $ratinghtml .= '<a href="#"><i class="fa fa-star"></i></a>';
                }
                for ($b = 1; $b <= (5 - $checkUlasan->rating); $b++) {
                    $ratinghtml .= '<a href="#"><i class="fa fa-star-o"></i></a>';
                }
                $ratinghtml .= '</div>';
                $html .= '<td align="left">' . $ratinghtml . '<p style="max-width: 100px;word-break: break-all;font-size:11.5px">' . $checkUlasan->text . '</p></td>';
            } else {

                $adaKomplain = $this->checkout->checkComplaint($orderId);

                if ($customerOrder->status == 7) {
                    $html .= '<td align="center" width="180px"><a href="#" class="btn btn-aksi btn-success btn-ok" data-product-id="' . $product->id . '" data-merchant-id="' . $merchant->id . '" data-id="' . $orderId . '"><i class="fa fa-check"></i> Ok</a> <a href="' . base_url() . 'Cart/opencomplaint?order=' . $rowOd->orderId . '&merchant=' . $merchant->id . '&product=' . $product->id . '" target="blank" class="btn btn-aksi btn-danger"><i class="fa fa-times"></i> Komplain </a></td>';
                } elseif ($customerOrder->status == 8) {
                    $html .= '<td align="center"><span style="color:red">' . $show_reason_reject . '</span></td>';
                } else {
                    if ($rowOd->status == 2 || $rowOd->status == 3 || $rowOd->status == 4) {
                        $html .= '<td align="center"><span style="color:red">' . $show_reason_reject . '</span></td>';
                    } else {

                        if ($adaKomplain == 'no') {
                            if ($customerOrder->status == 6) {
                                $html .= '<td align="center"><a href="#" class="btn btn-aksi btn-success btn-ok" data-product-id="' . $product->id . '" data-merchant-id="' . $merchant->id . '" data-id="' . $orderId . '"><i class="fa fa-check"></i> Ok</a> <a href="' . base_url() . 'Cart/opencomplaint?order=' . $rowOd->orderId . '&merchant=' . $merchant->id . '&product=' . $product->id . '" target="blank" class="btn btn-aksi btn-danger"><i class="fa fa-times"></i> Komplain </a></td>';
                            } else {
                                $html .= '<td align="center"><span class="status-barang">Konfirmasi Penerimaan Barang Dahulu</span><a href="#" style="display: none;" class="btn btn-aksi btn-success btn-ok" data-product-id="' . $product->id . '" data-merchant-id="' . $merchant->id . '" data-id="' . $orderId . '"><i class="fa fa-check"></i> Ok</a> <a href="' . base_url() . 'Cart/opencomplaint?order=' . $rowOd->orderId . '&merchant=' . $merchant->id . '&product=' . $product->id . '" style="display: none;" target="blank" class="btn btn-aksi btn-danger"><i class="fa fa-times"></i> Komplain </a></td>';
                            }
                        } else {
                            $html .= '<td align="center" width="180px"><a class="btn btn-danger retur-modal" data-complaint-id="' . (isset($complaintId->id) ? $complaintId->id : '') . '" data-product-id="' . $product->id . '" data-merchant-id="' . $merchant->id . '"><i class="fa fa-truck"></i> Retur Barang</a></td>';
                        }
                        //                    <span class="status-barang">Barang Sedang dalam Proses Pengiriman</span>
                    }
                }
            }





            $html .= '</tr>';

            $no++;

        endforeach;
        //endforeach;

        //                $rand = isset($response->uniqueCode)?$response->uniqueCode:0;
        //$total = $customerOrder->netTotal + $rand + $customerOrder->totalShippingCost;

        $html .= '<tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="6">
                        <b>Subtotal</b>
                    </td>
                    <td align="right">' . 'Rp ' . number_format($subTotal) . '</td>
                </tr>
                <tr>
                    <td colspan="5"><b>Biaya Pengiriman</b></td>
                    <td align="right">@' . $customerOrder->totalWeight . ' Gram</td>
                    <td align="right">' . 'Rp ' . number_format($customerOrder->totalShippingCost) . '</td>
                </tr>
                <tr>
                    <td colspan="6"><b>Total Pembayaran</b></td>
                    <td align="right">' . '<b>Rp ' . number_format($customerOrder->netTotal) . '</b>' . '</td>
                </tr>
                </tbody>
            </table>';

        echo json_encode(array('html' => $html));
    }

    function searchForKey($id, $array, $column)
    {
        foreach ($array as $key => $val) {
            if ($val->$column === $id) {
                return $key;
            }
        }
        return null;
    }


    public function getProductDetail()
    {
        $id = $this->input->get('id');
        $result = $this->db->get_where('Product', array('id' => $id))->row();
        echo json_encode($result);
    }

    public function orderDelivered()
    {
        $orderId = $this->input->get('orderId');

        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/order/delivered?access_token=' . $this->session->userdata('token') . '&orderId=' . $orderId,
            'data' => [
                'orderId' => $orderId
            ]
        ];

        $api = $this->api->getApiData($params);

        echo json_encode($api);
    }

    public function orderComplaint()
    {
        $orderId = $this->input->get('orderId');

        $data = [
            'status' => 6
        ];

        $this->db->where('id', $orderId);
        $query = $this->db->update('Order', $data);

        echo json_encode($query);
    }

    public function getDetailOrderByIdForUlasan()
    {
        $orderId = $this->input->get('orderId');
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/order?access_token=' . $this->session->userdata('token') . '&orderId=' . $orderId . '',
            'data' => array()
        ];

        $api = $this->api->getApiData($params);
        //echo '<pre>';print_r($api);
        $customerOrder = $api->result;
        $btn = '';
        $html = '';
        $html .= '<form action="' . base_url() . 'Cart/barangDiterima" method="POST" id="' . $customerOrder->id . '">';
        $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
        $btn .= '<a class="btn btn-info" title="Lihat invoice" href="' . base_url() . 'Cart/invoice?invoiceId=' . $customerOrder->invoiceId . '" target="_blank"> <i class="fa fa-file"></i> Lihat Invoice </a> ';
        $html .= '<input type="hidden" name="orderId" id="orderId" value="' . $customerOrder->id . '">';
        $btn .= '<button type="submit" id="submit_btn" class="btn btn-success"> <i class="fa fa-check"></i> Konfirmasi Penerimaan Barang  </button>';

        $html .= '<tr>
                    <td colspan="8" align="left">
                    <h3>' . $customerOrder->invoiceId . '</h3>
                    </td>
                </tr>';

        //foreach( $customerOrder as $rowOrder) :
        $merchant = $customerOrder->merchant;
        $notes = $customerOrder->notes;
        /*isset rejected*/
        $reject = isset($notes->reject) ? $notes->reject->items : array();


        $html .= '<tr>
                        <td colspan="5"><b>' . strtoupper($merchant->name) . '</b></td>
                        <td colspan="3" align="right"><b>' . $customerOrder->id . '</b> </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>Images</td>
                        <td>Nama Produk</td>
                        <td>Notes</td>
                        <td style="width:100px" align="center">Qty</td>
                        <td style="width:100px" align="center">Berat</td>
                        <td style="width:100px" align="right">Harga Satuan</td>
                        <td style="width:100px" align="right">Subtotal</td>
                        
                    </tr>
                <tbody>';

        foreach ($customerOrder->items as $rowOd) :
            $product = $rowOd->product;
            $priceNett = $rowOd->subtotalPrice + $rowOd->subtotalMargin - $rowOd->subtotalDiscount;
            $subTotal[] = $priceNett;
            $subTotalWeight[] = $rowOd->subtotalWeight;

            /*array search reject*/
            $key = array_search($product->id, array_column($reject, 'productId'));
            $show_reason_reject = isset($reject[$key]->reason) ? '' . $reject[$key]->reason : '';

            /*komplain*/
            $komplain = $this->cart->getComplaintByOrder($customerOrder->id, $product->id);

            $komplain_status = ($komplain->num_rows() > 0) ? ($komplain->row()->status == 3) ? '<span style="color:green"> <i class="fa fa-check"></i> ' . $komplain->row()->name . '</span>' : '( ' . $komplain->row()->name . ' )' : '';

            $subject_komplain = ($komplain->num_rows() > 0) ? '' . $komplain->row()->subject . '<br>' . $komplain_status . ' ' : '';

            $icon = ($show_reason_reject != '') ? '<span style="color:red"><i class="fa fa-times"></i></span>' : ($komplain->num_rows() > 0) ? '<span style="color:red"><i class="fa fa-times"></i></span>' : '<span style="color:green"><i class="fa fa-check"></i></span>';

            /*status order*/
            $status_order = $this->cart->getStatusOrder($rowOd->orderId);

            $html .= '<tr>
                        <td align="center"></td>
                        <td align="left"><img src="' . IMG_PRODUCT . $product->images[0]->thumbnail . '" width="100px"></td>
                        <td align="center">' . $product->name . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="center">' . $rowOd->quantity . '</td>
                        <td align="center">' . $rowOd->subtotalWeight . ' Gram</td>
                        <td align="right">' . 'Rp ' . number_format($rowOd->netTotal) . '</td>
                        <td align="right">' . 'Rp ' . number_format($priceNett) . '</td>
                        
                    </tr>';

        /*$html .= '<tr>';
        $html .= '<td colspan="4"><input type="checkbox" name="productId[]" class="checkbox_confirm" value="'.$product->id.'" style="margin:-15px 10px!important; width:15px;float:left" checked> <span>Barang sudah diterima dengan baik</span></td>';
        $html .= '<td colspan="4" align="right"><a href="'.base_url().'Cart/opencomplaint?order='.$rowOd->orderId.'&merchant='.$merchant->id.'&product='.$product->id.'" target="blank" class="btn btn-danger"><i class="fa fa-times"></i> Komplain </a></td>';
        $html .= '<tr>';*/

        endforeach;
        //endforeach;

        $rand = isset($response->uniqueCode) ? $response->uniqueCode : 0;
        //$total = $customerOrder->netTotal + $rand + $customerOrder->totalShippingCost;

        $html .= '<tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="7">
                        <b>Subtotal</b>
                    </td>
                    <td align="right">' . 'Rp ' . number_format($customerOrder->netTotal) . '</td>
                </tr>
                <tr>
                    <td colspan="6">Biaya Pengiriman</td>
                    <td align="right">@' . $customerOrder->totalWeight . ' Gram</td>
                    <td align="right">' . 'Rp ' . number_format($customerOrder->totalShippingCost) . '</td>
                </tr>
                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="7"><b>Total Pembayaran</b></td>
                    <td align="right">' . '<b>Rp ' . number_format($customerOrder->netTotal) . '</b>' . '</td>
                </tr>
                </tbody>
            </table></form>';

        //$result = $this->checkout->getCustomerInvoiceById($invoiceId);
        /*echo '<pre>';
        echo $html;*/

        echo json_encode(array('html' => $html));
    }

    private function checkLogin()
    {
        /* cek session logged in */
        if (!($this->session->userdata('logged_in'))) {
            echo '<meta http-equiv="refresh" content="0;URL=\'' . base_url() . 'Login' . '\'" />';
            echo '<script>parent.window.location.assign("' . base_url() . 'Login")</script>';
        }
    }

    public function traceOrder()
    {
        $this->checkLogin();
        $orderId = $this->input->get('orderId');
        $params = [
            'link' => API_DATA . 'Customers/order/trace?access_token=' . $this->session->userdata('token') . '&orderId=' . str_replace(' ', '', $orderId) . '&id=' . $this->customerId . '',
            'data' => array()
        ];

        $html = '';
        $response = $this->api->getApiData($params);
        $html .= '<b><i class="fa fa-credit-card"></i> ' . $orderId . '</b>';
        $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
        $html .= '<tr>
            <td>No Resi</td>
            <td align="left">-</td>
            </tr>';
        $html .= '<tr>
            <td>Status</td>
            <td align="left">' . $response->error->message . '</td>
            </tr>';
        $html .= '<tr>
            <td>Service</td>
            <td align="left">-</td>
            </tr>';
        $html .= '<tr>
            <td>Dikirim Tanggal</td>
            <td align="left">-</td>
            </tr>';
        $html .= '<tr>
            <td>Alamat Tujuan</td>
            <td align="left">-</td>
            </tr>';
        $html .= '<tr><td colspan="2"><b><i class="fa fa-history"></i> RIWAYAT PENGIRIMAN</b></td></tr>';
        $html .= '<tr>
            <td>Tanggal </td>
            <td>Keterangan</td>
            </tr>';
        for ($i = 1; $i <= 2; $i++) {
            $html .= '<tr>
            <td>' . $this->tanggal->formatDateTime(date('Y-m-d H:i:s')) . '</td>
            <td>Status ' . $i . ' </td>
            </tr>';
        }
        $html .= '</table">';


        echo json_encode(['message' => $html]);
    }

    public function tagihan()
    {
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Tagihan Transaksi', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /* all data cart by merchant */
        $data['customerOrder'] = $this->checkout->getCustomerInvoice($this->customerId, ['1', '2', '3']);
        //$data['customerOrder'] = $this->checkout->getCustomerInvoiceByApi($this->customerId);
        //print_r($this->session->userdata('token'));die;
        $data['customerId'] = $this->customerId;

        $new_array = array();
        foreach ($data['customerOrder'] as $key => $value) {
            if (is_null($value->notificationId) === false) {
                $new_array[$key] = $value;
            }
        }
        //        echo '<pre>'; print_r($data['customerOrder']);die;
        $data['countNotif'] = count($new_array);

        $data['page'] = 'pembayaran';
        $data['title'] = 'Status Pemesanan Transfer - Halal Shopping';
        /* load view */
        $this->template->load($data, 'tagihan', 'tagihan', 'Cart');
    }
}
