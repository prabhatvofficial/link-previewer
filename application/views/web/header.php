<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?php if($page == 'home'){echo "Website response checker - real-time problems & downtime reports - Is Web Up or Down";}
            if($page == 'link_preview'){ ?><?= $domain ?> reviews | Read Customer Service Reviews of <?= $domain ?> <?php } ?>
        </title>
        <meta name="description" content="<?php if($page == 'home'){echo "Check any website description.";}else if($page == 'link_preview'){ ?>Show description<?php } ?>">
    
    
        <link rel="canonical" href="<?php if($page == 'home'){echo base_url() ;}else if($page == 'link_preview'){ ?><?= base_url(); ?>/link-preview/<?= $domain ?><?php }?>" />
	    <meta property="og:locale" content="en_US" />
	    <meta property="og:type" content="article" />
	    <meta property="og:title" content="<?php if($page == 'home'){echo "Website response checker - real-time problems & downtime reports - Is Web Up or Down";}else if($page == 'link_preview'){ ?><?= $domain ?> reviews | Read Customer Service Reviews of <?= $domain ?><?php }?>" />
	    <meta property="og:description" content="<?php if($page == 'home'){echo "Is Web Up or Down helps you to check whether a website is currently up or down. Find out the header response codes within seconds.";}else if($page == 'link_preview'){ ?>On this page, you will be able to read the customer reviews regarding <?= $domain ?><?php } ?>" />
	    <meta property="og:url" content="<?php if($page == 'home'){echo "https://iswebupordown.com";}else if($page == 'link_preview'){ ?>https://iswebupordown.com/reviews/<?= $domain ?><?php }?>" />
	    <meta property="og:site_name" content="Is Web Up or Down" />
    
  

  <?php
  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $key = '/web/index/';
    if (strpos($actual_link, $key) == false) {
        
    }
    else {
       echo '<META NAME="robots" CONTENT="noindex,follow">';
    }
  ?>
  
  
  <?php //if($page == 'review'){echo '<META NAME="robots" CONTENT="noindex,follow">';} ?>
 <?php if($page == '404'){echo '<META NAME="robots" CONTENT="noindex,follow">';} ?>
<link href="/assets/images/favicon2.png" rel="icon" type="image/png" />
<link href="/assets/images/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" referrerpolicy="no-referrer" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="<?= base_url('assets/css/styles.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('assets/flags/css/flag-icons.min.css'); ?>"/>

    </head>
    <body>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <div class="container">
                <?php 
            //     $sql="select * from setting where id= '1'";    
            // $query = $this->db->query($sql);
            // $sitelogo =  $query->result_array();
                ?>
                <a class="navbar-brand" href="<?= base_url(); ?>"><img src="<?php // = base_url('uploads/').$sitelogo['0']['sitelogo']; ?>" style="width:125px;" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <form action="<?= base_url('query')?>" method="GET" class="d-flex ms-auto my-2">
                        <input type="text" name="url" class="form-control me-2" placeholder="e.g. example.com">
                        <input type="submit" class="btn btn-primary" value="Check Website">
                    </form>
                </div>
            </div>
        </nav>
        <div class="container">
            
            

