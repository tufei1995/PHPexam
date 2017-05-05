<?php
defined('THINK_PATH') or exit();

class SendCloud {

    public function __construct($opt) {
        $this->sc['api_user'] = C('SC_API_USER');
        $this->sc['api_key'] = C('SC_API_KEY');
        $this->sc['from'] = C('SC_FROM');
        $this->sc['fromname'] = C('SC_FROMNAME');
        $this->sc = array_merge($this->sc, $opt);
        $this->checkField('api_user,api_key,from,fromname,to');
    }

    public function send() {
        if (empty($this->sc['files'])) {
            $options = $this->deal_normal();
        } else {
            $options = $this->deal_attachment();
        }
        $context = stream_context_create($options);
        $result = file_get_contents('http://www.sendcloud.net/webapi/mail.send.json', FILE_TEXT, $context);
        $result = json_decode($result, true);
        //$this->mail_log($result);
        if ($result['message'] == 'success') {
            return true;
        } else {
            $this->error = $result['errors'];
            return false;
        }
    }
    public function mail_log($result) {
        $data['email'] = $this->sc['to'];
        $data['subject'] = $this->sc['subject'];
        $data['status'] = $result['message'] == 'success' ? 'FINISHED' : 'FAILED';
        $data['files'] = $this->sc['files'] ? serialize($this->sc['files']) : 'NO_FILES';
        $data['created_time'] = date('Y-m-d H:i:s');
        if ($result['message'] == 'error') $data['error_info'] = $result['errors'][0];
        $insert = array(
            'subject' => $this->sc['subject'],
            'content' => $this->sc['html'],
            'cc' => $this->sc['cc'],
            'content' => $this->sc['bcc']
        );
        $data['sc'] = serialize($data);
        M('mail')->add($data);
    }

    public function error_info() {
        return $this->error;
    }

    //检测必传字段合法性
    private function checkField($field) {
        $fields = explode(',', $field);
        foreach ($fields as $key => $v) {
            if (empty($this->sc[$v])) {
                exit('missing param: ' . $v);
            }
        }
    }
    private function deal_normal(){
        $data = http_build_query($this->sc);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            )
        );

        return $options;
    }

    private function deal_attachment(){
        $eol = "\r\n";
        $data = '';
        $boundary = md5(time());
        foreach ($this->sc as $key => $value) {
            $data.= '--' . $boundary . $eol;
            $data.= 'Content-Disposition: form-data; ';
            $data.= "name=" . $key . $eol . $eol;
            $data.= $value . $eol;
        }
        foreach ($this->sc['files'] as $v) {
            $content = file_get_contents($v);
            $data.= '--' . $boundary . $eol;
            $data.= 'Content-Disposition: form-data; name="file"; filename="' . preg_replace('/^.+[\\\\\\/]/', '', $v) . '"' . $eol;
            $data.= 'Content-Type: application/octet-stream' . $eol;
            $data.= 'Content-Transfer-Encoding: binary' . $eol . $eol;
            $data.= $content . $eol;
        }
        $data.= "--" . $boundary . "--" . $eol . $eol;
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: multipart/form-data;boundary=' . $boundary . $eol,
                'content' => $data
            )
        );

        return $options;
    }
}
