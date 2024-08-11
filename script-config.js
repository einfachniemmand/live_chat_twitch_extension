document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function(){
                    document.getElementById('loading_bar').style.width = '98%';
                },50)
                setTimeout(function(){document.getElementById('outer_loading_bar').style.opacity = '0'},2700)
                setTimeout(function(){document.getElementById('content').style.opacity = '1'},3000);
});