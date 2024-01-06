<!--Icon Css-->
<link rel="stylesheet" href="<?=base_url('assets/');?>mobile/css/icofont.min.css">
<link rel="stylesheet" href="<?=base_url('assets/');?>mobile/css/icofont.css">
<div class="country-code-container">
    <br>
    <br>
    <div class="login-section-box">
        <section class="deals_homesec">
            <div class="heading-section">
                <h1>Select Country/Region</h1> 
                <p id="clountry_close">Cancel</p>
            </div>
        </section>

        <div>
            <input type="search" id="country_code_search" class="form-control btn-search" name="country_code_search" placeholder="search">
            <i class="icofont-search search-button"></i>
        </div>

        <div class="country_code_list">

            <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>

                <?php $CountryCodeList = explode('+', $countryCodeValue); ?>
                <div class="country-code-section" data-countrycode="<?=$countryCodeKey?>">
                    <p class="country-name"><?=$CountryCodeList['0']?></p>
                    <p class="country-code">+<?=$CountryCodeList['1']?></p>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<script>

//showing country code list.
$('.country-code-section').on('click', function() {

    var country_code = $(this).data('countrycode');
    // console.log(country_code);
    $('.country-code-container').hide();
    $('#country_code').show();
    $('#country_code').val(country_code);
    $(".signin_botomstripe").show();
});

//slecting value after hiding country code list.
$('#country_code').on('click', function() {
   $('.country-code-container').show();
    $('#country_code').hide();
    $(".signin_botomstripe").hide();

});

$('#clountry_close').on('click' , function(){
    $('.country-code-container').hide();
    $(".signin_botomstripe").show();
    $('#country_code').show();
});


// searching text in search input field.
$('#country_code_search').keyup(function(event){
     var search_input = $(this).val();
     // console.log(search_input);
     
    var searchTerm = search_input; // Replace "match" with the word you want to search for

    $('.country-name').each(function() {
      var countryName = $(this).text().trim().toLowerCase();
      var countryCode = $(this).parent('.country-code-section').children('.country-code').html();

      if (countryName.includes(searchTerm.toLowerCase())  || countryCode.includes(searchTerm) ) {
        $(this).closest('.country-code-section').show();
      } else {
        $(this).closest('.country-code-section').hide();
      }
    });

});

</script>