<?php
$data['title'] = 'Produtos - Montink';
$data['content'] = $this->load->view('products/content', $data, true);
$this->load->view('layouts/base', $data);
?> 