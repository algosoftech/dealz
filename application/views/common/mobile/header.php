    <?php
            $url_save = $this->uri->segment(1);
            if($url_save == ''):
                $active = 'active';
            elseif($url_save == 'our-products'):
                $active = 'active';
            endif;
        ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dealz Arabia | <?=$page;?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!--Google Font-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <!--Bootstrap Css-->
    <link rel="stylesheet" href="<?=base_url('assets/');?>mobile/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <!--Icon Css-->
    <link rel="stylesheet" href="<?=base_url('assets/');?>mobile/css/icofont.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>mobile/css/icofont.css">
    <!--Styel Css-->
    <link rel="stylesheet" href="<?=base_url('assets/');?>mobile/css/mob-style.css">

    <meta name="google-site-verification" content="gsaze8XDsNotiOg2ZPZgUWMzb_riwQ_fLGvFyguz22I" />
    <meta name="facebook-domain-verification" content="br469bduqxi9ktcquu78izycc2z1kf" />
    <meta name="p:domain_verify" content="4b49a42ad1da3a8e25c7e8921a53e5f4"/>  
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '6490715127640549');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=6490715127640549&ev=PageView&noscript=1"
    /></noscript>
    <script>
    !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
    },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
    a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
    twq('config','oern9');
    </script>
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '667672865118226');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=667672865118226&ev=PageView&noscript=1"/></noscript>
    <!-- <script>
    !function (w, d, t) {
      w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

      ttq.load('CGDDFTBC77U845ORK4U0');
      ttq.page();
    }(window, document, 'ttq');
    </script> -->
    <script>
    !function (w, d, t) {
      w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

      ttq.load('CH95SJBC77U2I5R8NVOG');
      ttq.page();
    }(window, document, 'ttq');
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2NZ7QDVJC2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-2NZ7QDVJC2');
    </script>

</head>
<body>

    <?php if($active == 'active'):  ?>

    <div class="main_wrapper">
        <div class="mob_wrapper">
            <header>
                <div class="container row">
                    <div class="col">
                        <?php if($this->session->userdata('DZL_USERID')): ?>
                        <div class="head_profile">
                            <a href="<?=base_url("profile");?>"><i class="icofont-ui-user"></i> <?=@$this->session->userdata('DZL_USERNAME')?> </a>
                        </div>
                    <?php endif;?>

                    </div>
                    <div class="col text-center">
                        <div class="head_logo">
                            <img src="<?=base_url('assets/');?>/img/white-logo.gif" />
                        </div>
                    </div>
                    <div class="col text-end">
                        <div class="notification">
                            <a href="<?=base_url("notification");?>"><i class="icofont-notification"></i></a>
                        </div>
                    </div>
                </div>
            </header>
    <?php endif; ?>