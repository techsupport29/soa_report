const truncate = () => {
    swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        input: "password",
        inputPlaceholder: "Enter Your Password",
        inputAttributes: {
            autocapitalize: "off",
            autocorrect: "off",
        },
        customClass: {
            input: "form-control",
        },
        inputAttributes: {
            autocapitalize: "off",
        },
        showClass: {
            popup: "animate__animated animate__fadeInDown",
        },
        hideClass: {
            popup: "animate__animated animate__fadeOutUp",
        },
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Clear All!",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        showLoaderOnConfirm: true,

        preConfirm: (login) => {
            if (login == "") {
                swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please Enter Valid Password",
                });
            }
        },
    }).then((result) => {
        if (result.isConfirmed) {
            axios
                .get("api/validate/" + result.value)
                .then((response) => {
                    if (response.data == "success") {
                        Toast.fire({
                            icon: "success",
                            title: "Successfully Deleted",
                        });
                        Fire.$emit("AfterCreate");
                        // location.reload(); //to be removed
                    } else {
                        swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Password doesnt match in our database",
                        });
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                        footer: error,
                    });
                    console.log(error);
                });
        }
    });
};

export { truncate };
