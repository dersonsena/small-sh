$(document).ready(function () {
    $('button.btn-toggle-history').on('click', function () {
        const $button = $('button.btn-toggle-history');
        $('.shortened-urls').fadeToggle('fast', 'linear', function () {
            if ($(this).css('display') === 'block') {
                $button.find('span').html('Esconder meu histórico')
            } else {
                $button.find('span').html('Ver meu histórico')
            }
        });
    });

    $('button.copy-button').on('click', function () {
        $(this).removeClass('btn-outline-primary')
        $(this).addClass('btn-success')
        $(this).html('<i class="fas fa-check-circle"></i> Copiado!')
    });

    $('button.btn-shorten').on('click', function (e) {
        e.preventDefault();
        const $inputUrl = $('input#huge-url');
        const $btnShorten = $(this);

        if ($inputUrl.val() === '') {
            alert('Informe sua URL para poder encurtar.')
            $inputUrl.select();
            return false;
        }

        const regex = /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g;

        if (!regex.test($inputUrl.val())) {
            alert('Informe uma URL válida para poder encurtar.')
            $inputUrl.select();
            return false;
        }

        const originalContent = $btnShorten.html();

        $.ajax({
            url: `${baseUrl}/api/public/shorten`,
            type: 'post',
            data: { huge_url : $inputUrl.val() },
            beforeSend : function() {
                $inputUrl.prop('disabled', true);
                $btnShorten.prop('disabled', true);
                const $span = $('<span/>').html('Preparando sua URL...');
                $btnShorten.empty().append($span);
            }
        }).done(function(payload) {
            $btnShorten.html(originalContent);
            $btnShorten.removeAttr('disabled');
            $inputUrl.removeAttr('disabled');
            $('form#form-shorten').hide();
            $('div.history-wrapper').hide();

            const $divResult = $('div.shortened-url-result');
            $divResult.css('display', 'flex');
            $divResult.find('a').html(payload.data.shortened);
        }).fail(function(jqXHR, textStatus, msg) {
            $btnShorten.html(originalContent);
            $btnShorten.removeAttr('disabled');
            $inputUrl.removeAttr('disabled');
        });
    });

    $('button.new-url').on('click', function () {
        $('form#form-shorten').show();
        $('div.history-wrapper').show();
        $('div.shortened-url-result').css('display', 'none');
        const $input = $('input#huge-url');
        $input.val('');
        $input.select();
    });
})