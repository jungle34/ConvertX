

document.addEventListener('click', (event) => {

    clickGetData(event.target);

    $(event.target).css("backgroud-color", '#000')

});

function checkToken(){
    if(TOKEN){
        return TOKEN
    }else{
        return false
    }
}

function clickGetData(el) {
    let id = el.id != "" ? el.id : null;
    let text = el.textContent != "" ? el.textContent : null;
    let tagName = el.tagName != "" ? el.tagName : null;
    let location = window.location.href != "" ? window.location.href : null;
    let device = navigator.userAgent != "" ? navigator.userAgent : null;

    let className = getClassName(text);

    var elemento = {
        id: id,
        text: text,
        class: className,
        type: tagName,
        locationUrl: location,
        device: device
    }
    
    apiLogging(elemento);

}

function apiLogging(element) {
    let url = 'http://192.168.100.12/convertx/api/Logger.php'
    let token = checkToken();
    
    $.ajax({
        url,
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`
        },
        data: element,
        success: function(data){
            if(data.TYPE == 'SUCCESS'){
                console.log(data.MSG);
            }else{
                console.error(data.MSG);
            }
        }
    })
}

function getClassName(text) {
    if (text.toLowerCase().includes('compra')) {
        return 'checkout';
    } else if (text.toLowerCase().includes('carrinho')) {
        return 'cart';
    } else if (text.toLowerCase().includes('ver')) {
        return 'getMore';
    } else if (text.toLowerCase().includes('obter')) {
        return 'signup';
    } else if (text.toLowerCase().includes('inscreva-se')) {
        return 'signup';
    } else if (text.toLowerCase().includes('comece')) {
        return 'signup';
    } else if (text.toLowerCase().includes('experimente')) {
        return 'getTry';
    } else if (text.toLowerCase().includes('saiba')) {
        return 'getMore';
    } else if (text.toLowerCase().includes('mais')) {
        return 'getMore';
    } else if (text.toLowerCase().includes('descubra')) {
        return 'getMore';
    } else if (text.toLowerCase().includes('baixar')) {
        return 'download';
    }
}



