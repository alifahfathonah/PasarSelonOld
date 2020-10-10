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

    public function riwayatTransaksi()
    {
        $this->checkLogin();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Riwayat Transaksi', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        /* all data cart by merchant */
        $data['customerOrder'] = $this->checkout->getCustomerInvoice($this->customerId, ['6', '7', '12']);
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
        $data['customerOrder'] = $this->checkout->getCustomerInvoice($this->customerId, ['1', '2', '3']);
        //print_r($this->db->last_query());die;
        $data['customerId'] = $this->customerId;

        $new_array = array();
        foreach ($data['customerOrder'] as $key => $value) {
            if (is_null($value->notificationId) === false) {
                $new_array[$key] = $value;
            }
        }
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
        $data['customerOrder'] = $this->checkout->getCustomerInvoiceById($invoiceId);
        $data['page'] = 'invoice';

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
    public function pesanan_gagal()
    {
        $this->checkLogin();
        $this->breadcrumbs->push('Order Sedang Dikirim', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        //$data['allRejected'] = $this->checkout->rejectedInvoice($this->customerId);
        $data['allRejected'] = $this->checkout->getCustomerInvoice($this->customerId, ['4']);;
        //print_r(expression)
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

        //echo '<pre>';print_r($data['allDeliveredOrder']);die;
        //        echo '<pre>';
        //        echo print_r($data['allDeliveredOrder']);
        //        exit;

        // Pagination Configuration
        $config = array();
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config["base_url"] = base_url('Cart/pengiriman');
        $config["total_rows"] = $this->checkout->countGetOrder($this->customerId, [3, 4, 5, 6]);
        $config["per_page"] = 6;
        //        $config["uri_segment"] = 2;

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $query_string = $_GET;
        if (isset($query_string['page'])) {
            unset($query_string['page']);
        }

        if (count($query_string) > 0) {
            $config['suffix'] = '&' . http_build_query($query_string, '', "&");
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_string, '', "&");
        }

        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $config["page"] = $page;
        $after_page = $page + $config["per_page"];

        $config['after_page'] = ($after_page > $config['total_rows']) ? $config['total_rows'] : $after_page;

        $this->pagination->initialize($config);

        $data['allDeliveredOrder'] = $this->checkout->getOrderWithLimit($this->customerId, [5, 6], $config["page"], $config["per_page"]);

        $data['page'] = 'pengiriman';
        $data["links"] = $this->pagination->create_links();
        $data['title'] = 'Pesanan dikirim - Halal Shopping';

        //        print_r($config["total_rows"]);
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

    public function getDetailOrder()
    {
        $invoiceId = $this->input->get('invoiceId');
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/invoice?access_token=' . $this->session->userdata('token') . '&invoiceId=' . $invoiceId . '',
            'data' => array()
        ];

        $api = $this->api->getApiData($params);
        $customerOrder = $api->result;
        //echo '<pre>';print_r($api);
        $btn = '';
        $html = '';
        $html .= '<table class="table table-striped" width="100%" style="font-size:11.5px">';
        $btn .= '<a class="btn btn-info" title="Lihat invoice" href="' . base_url() . 'Cart/invoice?invoiceId=' . $customerOrder->id . '" target="_blank"> <i class="fa fa-file"></i> Lihat Invoice </a> ';
        if ($customerOrder->status == 1) {
            if ($customerOrder->method == 0 || $customerOrder->method == 3) {
                $btn .= '<a class="btn btn-danger" title="Lanjutkan pembayaran" href="' . base_url() . 'Cart/Checkout/payment_method?invoiceId=' . $customerOrder->id . '"><i class="fa fa-money"></i> Lanjutkan Proses</a> ';
            } elseif ($customerOrder->method == 1) {
                $btn .= '<a class="btn btn-success" title="Unggah bukti pembayaran" href="' . base_url() . 'Cart/unggah_bukti?invoiceId=' . $customerOrder->id . '"><i class="fa fa-upload"></i> Unggah Bukti</a>';
            }
        }

        $html .= '<tr>
                    <td colspan="8" align="right">
                    ' . $btn . '
                    </td>
                </tr>';

        foreach ($customerOrder->orders as $rowOrder) :
            $merchant = $rowOrder->merchant;
            $notes = $rowOrder->notes;
            /*isset rejected*/
            $reject = isset($notes->reject) ? $notes->reject->items : array();


            $html .= '<tr>
                        <td colspan="5"><b>' . strtoupper($merchant->name) . '</b> (' . $rowOrder->status . ') </td>
                        <td colspan="3" align="right"><b>' . $rowOrder->id . '</b> </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>Nama Produk</td>
                        <td>Catatan</td>
                        <td>Status</td>
                        <td style="width:100px" align="center">Qty</td>
                        <td style="width:100px" align="center">Berat</td>
                        <td style="width:100px" align="right">Harga Satuan</td>
                        <td style="width:100px" align="right">Subtotal</td>
                    </tr>
                <tbody>';

            foreach ($rowOrder->items as $rowOd) :
                $product = $rowOd->product;
                $priceNett = $rowOd->subtotalPrice + $rowOd->subtotalMargin - $rowOd->subtotalDiscount;
                $subTotal[] = $priceNett;
                $subTotalWeight[] = $rowOd->subtotalWeight;

                /*array search reject*/
                $key = array_search($product->id, array_column($reject, 'productId'));
                $show_reason_reject = isset($reject[$key]->reason) ? '' . $reject[$key]->reason : '';

                /*komplain*/
                $komplain = $this->cart->getComplaintByOrder($rowOrder->id, $product->id);

                $komplain_status = ($komplain->num_rows() > 0) ? ($komplain->row()->status == 3) ? '<span style="color:green"> <i class="fa fa-check"></i> ' . $komplain->row()->name . '</span>' : '( ' . $komplain->row()->name . ' )' : '';

                $subject_komplain = ($komplain->num_rows() > 0) ? '' . $komplain->row()->subject . '<br>' . $komplain_status . ' ' : '';

                $icon = ($show_reason_reject != '') ? '<span style="color:red"><i class="fa fa-times"></i></span>' : ($komplain->num_rows() > 0) ? '<span style="color:red"><i class="fa fa-times"></i></span>' : '<span style="color:green"><i class="fa fa-check"></i></span>';

                /*status order*/
                $status_order = $this->cart->getStatusOrder($rowOd->orderId);

                $html .= '<tr>
                        <td align="center">' . $icon . '</td>
                        <td>' . $product->name . '</td>
                        <td align="left">' . ucfirst($rowOd->notes) . '</td>
                        <td align="left">' . $status_order->status_name . '<br><span style="color:red">' . $show_reason_reject . ' ' . $subject_komplain . '</span>  </td>
                        <td align="center">' . $rowOd->quantity . '</td>
                        <td align="center">' . $rowOd->subtotalWeight . ' Gram</td>
                        <td align="right">' . 'Rp ' . number_format($priceNett) . '</td>
                        <td align="right">' . 'Rp ' . number_format($priceNett) . '</td>
                    </tr>';

            endforeach;
        endforeach;

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
                <tr>
                    <td colspan="7">Pajak</td>
                    <td align="right">' . 'Rp ' . number_format($customerOrder->totalTaxes) . '</td>
                </tr>
                <tr>
                    <td colspan="7">Kode Unik Pembayaran</td>
                    <td align="right">' . 'Rp ' . $rand . '</td>
                </tr>
                <tr style="background: #f1f1f1" bgcolor="#f1f1f1">
                    <td colspan="7"><b>Total Pembayaran</b></td>
                    <td align="right">' . '<b>Rp ' . number_format($customerOrder->netTotal) . '</b>' . '</td>
                </tr>
                </tbody>
            </table>';

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
}
