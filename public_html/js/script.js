$(document).ready(function () {
    let urlHistory = window.localStorage.getItem('url_history');

    if (urlHistory) {
        urlHistory = JSON.parse(urlHistory);
        for (let index in urlHistory) {
            if (index === '5') break;
            $('div.shortened-urls ul').append(getHistoryItemTemplate(urlHistory[index]));
        }
    }

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
        const $btn = $(this);
        navigator.clipboard.writeText($btn.attr('data-url'));

        $btn.removeClass('btn-light')
        $btn.addClass('btn-success')
        $btn.html('<i class="fas fa-check-circle"></i> Copiado!')
        setTimeout(function () {
            $btn.removeClass('btn-success')
            $btn.addClass('btn-light')
            $btn.html('<i class="far fa-copy"></i> Copiar')
        }, 3000)
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
            const urlHistory = window.localStorage.getItem('url_history');
            const currentHistory = urlHistory ? JSON.parse(urlHistory) : [];
            currentHistory.push(payload.data);
            currentHistory.sort(function (a, b) {
                return new Date(b.created_at) - new Date(a.created_at);
            });

            window.localStorage.setItem('url_history', JSON.stringify(currentHistory));

            $('div.shortened-urls ul').prepend(getHistoryItemTemplate(payload.data));

            $btnShorten.html(originalContent);
            $btnShorten.removeAttr('disabled');
            $inputUrl.removeAttr('disabled');
            $('form#form-shorten').hide();
            $('div.history-wrapper').hide();

            const $divResult = $('div.shortened-url-result');
            $divResult.css('display', 'flex');
            $divResult.find('a').attr('href', payload.data.shortened).html(payload.data.shortened);
            $divResult.find('button').attr('data-url', payload.data.shortened);
        }).fail(function(jqXHR, textStatus, msg) {
            if (jqXHR.status === 400) {
                const payload = jqXHR.responseJSON;
                let message = '';
                switch (payload.data.huge_url) {
                    case 'invalid-url':
                        message = 'Insira ua URL válida com "http://" ou "https://" para poder encurtar.';
                        break;
                    case 'empty-value':
                        message = 'Url longa não pode ser vazia.';
                        break;
                }
                alert(message);
                $inputUrl.select();
            }

            if (jqXHR.status === 500) {
                alert('Ops, ocorreu algum problema pra gerar sua URL. Por favor, tenta novamente.');
            }

            $btnShorten.html(originalContent);
            $btnShorten.removeAttr('disabled');
            $inputUrl.removeAttr('disabled');
        });
    });

    $('button.new-url').on('click', function () {
        $('form#form-shorten').show();
        $('div.history-wrapper').show();
        const $divResult = $('div.shortened-url-result');
        $divResult.css('display', 'none');
        $divResult.find('a').attr('href', '#');
        $divResult.find('button').attr('data-url', '');

        const $input = $('input#huge-url');
        $input.val('');
        $input.select();
    });
});

function getHistoryItemTemplate(data) {
    return `
        <li>
            <div class="long-url" title="${data.huge}">${data.huge.substring(0, 38)}...</div>
            <div class="short-url">
                <div class="link">
                    <a href="${data.shortened}" target="_blank" title="URL encurtada de ${data.huge}">
                        ${data.shortened}
                    </a>
                </div>
                <div class="copy">
                    <div class="d-grid gap-2">
                        <button data-url="${data.shortened}" class="btn btn-outline-primary copy-button">
                            <i class="far fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>
        </li>
    `;
}