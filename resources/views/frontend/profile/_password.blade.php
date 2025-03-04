<!-- Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel"><i class="fas fa-key"></i> {{__('Change the password')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <!-- CSRF token caché -->
                    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label for="old-password">{{__('Old password')}}</label>
                        <input type="password" class="form-control" id="old-password" name="old-password" required>
                        <div id="old-password-error" class="text-danger" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <label for="new-password">{{__('New password')}}</label>
                        <input type="password" class="form-control" id="new-password" name="new-password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">{{__('Confirm the new password')}}</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                        <div id="password-match-error" class="text-danger" style="display:none;"></div>
                        <div id="password-strength-error" class="text-danger" style="display:none;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="button" class="btn btn-primary" id="submitChangePassword">{{__('Change the password')}}</button>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script>

        $(document).ready(function () {
            let csrfToken = document.getElementById('csrf-token').value;



            $(document).ready(function() {
                $('#old-password').on('blur', function() {
                    let oldPassword = $(this).val(); // Récupère la valeur du champ
                    //let csrfToken = $('meta[name="csrf-token"]').attr('content'); // Récupère le token CSRF
                    $.ajax({
                        url: window.location.origin + '/verify-old-password',
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": csrfToken, // 🔥 Ajoute le token CSRF
                            "password": oldPassword
                        },
                        success: function(data) {
                            let errorDiv = $('#old-password-error');

                            if (!data.valid) {
                                errorDiv.text("Ancien mot de passe incorrect.").show();
                                toastr.error("Ancien mot de passe incorrect.", 'Echec');
                                $('#old-password').focus();
                            } else {
                                errorDiv.text("").hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur:', error);
                            alert("Une erreur est survenue : " + xhr.status + " - " + error);
                        }
                    });
                });
            });

           // Gestion du changement de mot de passe lors du clic sur le bouton de soumission
            $('#submitChangePassword').on('click', function (event) {
                event.preventDefault();

                let newPassword = $('#new-password').val();
                let confirmPassword = $('#confirm-password').val();
                let csrfToken = $('#csrf-token').val();
                // let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
                let passwordRegex = /^.{6,}$/; // Au moins 6 caractères, peu importe lesquels

                let isValid = true;
                let errorMessage = "";

                if (newPassword !== confirmPassword) {
                    errorMessage = "Les nouveaux mots de passe ne correspondent pas.";
                    isValid = false;
                }
                else if (!passwordRegex.test(newPassword)) {
                    errorMessage = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un caractère spécial.";
                    isValid = false;
                }

                if (isValid) {
                    $('#password-match-error').hide();
                    $.ajax({
                        url: '/change-password',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            "_token": csrfToken,
                            "newPassword": newPassword
                        },
                        success: function (data) {
                            if (data.success) {
                                toastr.success('Mot de passe changé avec succès !', 'Succès');

                                $('#old-password').val('');
                                $('#new-password').val('');
                                $('#confirm-password').val('');


                            } else {
                                $('#password-match-error').text("Erreur lors du changement de mot de passe.").show();
                                toastr.error(errorMessage, 'Echec');
                            }
                        },
                        error: function (xhr, status, error) {
                            toastr.error(errorMessage, 'Echec');
                            console.error('Erreur:', error);
                        },
                        complete: function(data) {
                            $('#changePasswordModal').fadeOut(300, function () {
                                $(this).modal('hide'); // Ferme la modal
                                $(this).removeClass('show'); // Enlève la classe "show" de la modal
                                setTimeout(function () {
                                    $('body').removeClass('modal-open'); // Enlève la classe modal-open du body après un léger délai
                                    $('.modal-backdrop').remove(); // Supprime le backdrop
                                }, 300); // Délai pour laisser la modal se fermer correctement
                            });

                        }
                    });
                } else {
                    toastr.error(errorMessage, 'Echec');
                    $('#password-match-error').text(errorMessage).show();
                }
            });
        });

    </script>
@endpush
