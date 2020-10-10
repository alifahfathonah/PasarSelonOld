<?php


class TimezoneApi extends MX_Controller
{
    public function setTimezone()
    {
        $zone = $this->security->xss_clean($this->input->post('timezone'));
        $this->session->set_userdata('timezone', $zone);

        echo json_encode(['zone' => $zone]);
    }

}