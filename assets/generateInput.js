$("#add-image").click(function (){

    let index = +$('#widgets-counter').val(index +1);
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
console.log(tmpl)
    $('#ad_images').append(tmpl);
    $('#widgets-counter').val(index + 1)
    handleDeleteButtons();
})

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function (){

    const target = this.dataset.target;

    $(target).remove();
    });
}

function updateCounter(){
    const count = +$('#add-image div.form-group').length;
    console.log(count)

    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();

let inputNotRequired = document.getElementById('ad_slug')
window.addEventListener("load", ()=>{
    inputNotRequired.removeAttribute('required')
})