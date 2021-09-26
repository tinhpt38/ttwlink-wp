
jQuery(document).ready(function ($) {

    $('.ttwlink-button-make').click(function () {
        var link = $('.ttw-link-input').val();
        $('.ttw-process').css('display','block');
        if (isUrlValid(link)) {
            console.log(link);
            console.log(ajax_object.ajax_url)
            $('.trw-error').css('display', 'none');

            const data = {
                action: 'ttw_make_url',
                des: link
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_object.ajax_url,
                data: data,
                success: function (response) {
                    console.log('success');
                    console.log(response);
                    $('.ttwlinkresult').attr('href', response.url);
                    $('.ttwlinkresult').html(response.url);
                    $('.ttwlinkresult').css('display', 'block');
                    $('.ttw-process').css('display','none')
                },
                error: function (response) {
                    console.log('error');
                    console.log(response);
                    $('.ttw-process').css('display','none')
                    $('.trw-error').css('display', 'block');
                    $('.trw-error').html('Something get wrong! Please try again!');
                }
            });


        } else {
            $('.trw-error').css('display', 'block');
            $('.ttw-process').css('display','none');
        }
    })

    function isUrlValid(userInput) {
        var res = userInput.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
        if (res == null)
            return false;
        else
            return true;
    }
});