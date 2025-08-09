



let carousel_s_form = document.getElementById('carousel_s_form');
let carousel_picture_inp = document.getElementById('carousel_picture_inp');



carousel_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_image();
});






function add_image() {

    if (!carousel_picture_inp.files.length) {
        alert("Please select an image!");
        return;
    }
    let data = new FormData();

    data.append('picture', carousel_picture_inp.files[0]);
    data.append('add_image', '');
    console.log("Sending file:", carousel_picture_inp.files[0]);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/carousel_crud.php", true);



    xhr.onload = function () {
        console.log(this.responseText);

        var myModal = document.getElementById('carousel-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 'inv_img') {
            alert('error', 'upload the valid picture formate!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'image should be less than 2 MB!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image upload failed.Server Down!');
        } else {
            alert('success', 'New image Added!');

            carousel_picture_inp.value = '';
            get_carousel();
        }




    }
    xhr.send(data);
}

function get_carousel() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/carousel_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');


    xhr.onload = function () {
        document.getElementById('carousel-data').innerHTML = this.responseText;


    }
    xhr.send('get_carousel');
}


function rem_image(val) {
    console.log("Attempting to remove image with ID:", val);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/carousel_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');


    xhr.onload = function () {
        console.log("Server Response:", this.responseText.trim()); // Debugging log

        if (this.responseText.includes("1")) {  // ✅ Allow partial matches of "1"
            alert('success', 'Image removed!');
            get_carousel(); // ✅ Refresh the carousel
        } else {
            alert('error', 'Server Response: ' + this.responseText);
        }
    };

    xhr.onerror = function () {
        alert('error', 'Request failed. Possible network/server issue.');
    };

    xhr.send("rem_image=" + encodeURIComponent(val));
}










window.onload = function () {
    get_carousel();
};
