<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <base href="<?php echo $this->config->item('base_url') ?>" />
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>images/icon.png">

  <title>LaffHub |
    <?=$title?>
  </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/select.jqueryui.min.css">
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/ionicons.min.css">
  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/jquery.dialog.css" />
  <link rel="stylesheet" href="css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="iconfont/material-icons.css">
  <link rel="stylesheet" href="css/components.min.css">
  <link href="css/plugins.min.css" rel="stylesheet" type="text/css" />

  <link href="css/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="css/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <?php 
        if(isset($api_token)){
          echo '<script>var meta_token  = "'.$api_token.'" </script>';
        }
        echo '<script>var _app_url = "'.$this->config->item('base_url').'"</script>';
        ?>
</head>

<body class="hold-transition skin-yellow sidebar-mini">
  <div class="wrapper">


    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view('sidemenu') ?>
    <?php $this->load->view('adminheader') ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h4>LaffHub</h4>

        <ol class="breadcrumb size-16">
          <li>
            <a href="<?php echo site_url(" Logout "); ?>">
              <i class="fa fa-home"></i> Home</a>
          </li>
        </ol>
      </section>
