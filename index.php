<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FCM & APNS Push Sender</title>
    <link rel="stylesheet" href="assets/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="assets/ace/ace.js"></script>

    <script type="text/javascript" src="assets/js/bootstrap.bundle.min.js"></script>



    <style>
        .jumbotron {
            padding-top: 3rem;
            padding-bottom: 3rem;
            margin-bottom: 0;
            background-color: #fff;
        }

        @media (min-width: 768px) {
            .jumbotron {
                padding-top: 6rem;
                padding-bottom: 6rem;
            }
        }

        .jumbotron p:last-child {
            margin-bottom: 0;
        }

        .jumbotron-heading {
            font-weight: 300;
        }

        .jumbotron .container {
            max-width: 40rem;
        }

        footer {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        footer p {
            margin-bottom: .25rem;
        }

        .ace_editor {
            border: 1px solid lightgray;
            margin: auto;
            height: 200px;
        }
        .scrollmargin {
            height: 80px;
            text-align: center;
        }

    </style>
</head>
<body>

<header>
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
            <a href="/" class="navbar-brand d-flex align-items-center">
                <strong>Pusher</strong>
            </a>
        </div>
    </div>
</header>

<main role="main">

    <section class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Pusher</h1>
            <p class="lead text-muted">
                Send push notification to android & ios devices.
                <br>
                This utility is only for testing purpose.
            </p>
            <p>
                <a href="#" class="btn btn-secondary my-2" id="android-btn">Android</a>
                <a href="#" class="btn btn-secondary my-2" id="ios-btn">IOS</a>
            </p>
        </div>
    </section>

    <div class="album py-5 bg-light">
        <div class="container">

            <div class="row justify-content-md-center">
                <div class="alert alert-primary col col-lg-9" style="display: none;" role="alert">
                    This is a primary alert—check it out!
                </div>
            </div>


            <div class="row justify-content-md-center">

                <div class="col col-lg-6" id="android-form">
                    <form>
                        <div class="form-group">
                            <label for="android[serverKey]">Server Key</label>
                            <input type="text" class="form-control" name="android[server_key]"
                                   placeholder="Server Key">
                        </div>

                        <div class="form-group">
                            <label for="android[title]">Device Token</label>
                            <input type="text" class="form-control" name="android[device_token]" placeholder="Device Token">
                        </div>
                        <div class="form-group">
                            <label for="android[json]">JSON</label>
                            <div id="android[json]" style="height: 400px;"></div>
                        </div>
                        <input type="hidden" name="isAndroid" value="true">

                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>

                <div class="col col-lg-6" id="ios-form" style="display: none;">
                    <form id="android-sending">
                        <div class="form-group">
                            <label for="ios[pemFile]">Pem File</label>
                            <input type="file" class="form-control-file" id="ios[pemFile]" name="ios[pemFile]">
                        </div>

                        <div class="form-group">
                            <label for="ios[pass_phrase]">Password or Passphrase</label>
                            <input type="text" class="form-control" id="ios[pass_phrase]" name="ios[pass_phrase]" placeholder="Password or passphrase">
                        </div>

                        <div class="form-group">
                            <label for="ios[device_token]">Device Token</label>
                            <input type="text" class="form-control" id="ios[device_token]" name="ios[device_token]" placeholder="Device Token">
                        </div>



                        <div class="form-group">
                            <label for="ios[json]">JSON</label>
                            <div id="ios[json]" style="height: 400px;"></div>
                        </div>

                        <!--<div class="form-group">
                            <label for="ios[is_production]">Production</label>
                            <input type="checkbox" id="ios[is_production]">
                        </div>-->

                        <input type="hidden" name="isIOS" value="true">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>


    <script type="text/javascript">


        var editorAndroid = ace.edit("android[json]", {
            theme: "ace/theme/clouds",
            mode: "ace/mode/json"
        });

        var editorIOS = ace.edit("ios[json]", {
            theme: "ace/theme/clouds",
            mode: "ace/mode/json"
        });



        function getFormData($form){
            var unindexed_array = $form.serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });

            return indexed_array;
        }


        jQuery(document).ready(function($) {
            let androidJson = JSON.stringify({
                "message":"message",
                "title":"title",
                "vibrate":1,
                "sound":1,
                "type":"brand",
                "id":"2",
                "image":"https://www.savyour.com.pk/assets/images/deal_avail_banner.png",
                "icon":"http://pngimg.com/uploads/google/google_PNG19635.png"
            }, null, 2);
            editorAndroid.setValue(androidJson, 1);

            let iosJson = JSON.stringify({
                "aps": {
                    "alert": {
                        "body": "Body for the notification.",
                        "title": "Super!"
                    },
                    "badge" : 0,
                    "sound":"default",
                    "mutable-content": 1,
                    "category": "imageCategory"

                },
                "data": {
                    "id": 2,
                    "outlet_id":240,
                    "type": "brand",
                    "image": "https://www.savyour.com.pk/assets/images/deal_avail_banner.png"
                }
            }, null, 2);


            editorIOS.setValue(iosJson, 1);
            let $androidForm = $("#android-form");
            let $iosForm = $("#ios-form");

            $("#android-btn").on('click', function(e){
                e.preventDefault();
                hideMessage();
                $iosForm.fadeOut(function() {
                    $androidForm.fadeIn();
                });
            });

            $("#ios-btn").on('click', function(e){
                e.preventDefault();
                hideMessage();
                $androidForm.fadeOut(function() {
                    $iosForm.fadeIn();
                });
            });

            $androidForm.on('submit', 'form', function(e) {
                e.preventDefault();
                let $this = $(this);
                let $data = getFormData($this);
                disableBtn($this, true);
                $data['android[json]'] = editorAndroid.getValue();
                $.ajax({
                    'url': 'sender.php',
                    'method': 'POST',
                    'data': $data
                }).success(function(response) {
                    response = JSON.parse(response);
                    console.log(response);
                    showMessage(response.status ? response.message : response.error, response.status);
                    disableBtn($this, false);
                }).error(function() {
                    showMessage("Some thing went wrong!", false);
                    disableBtn($this, false);
                })
            });

            $iosForm.on('submit', 'form', function(e) {
                e.preventDefault();
                let $this = $(this);
                let $data = $this.serializeArray();
                let fileObj = $("input[type='file']")[0];
                //disableBtn($this, true);


                let formData = new FormData();
                for(let $fieldIndex in $data) {
                    let field = $data[$fieldIndex];
                    formData.append(field.name, field.value);
                }

                formData.append('ios[json]', editorIOS.getValue());

                if(fileObj.files.length <= 0) {
                    showMessage("Please select .pem file", false);
                    return;
                }
                hideMessage();

                formData.append("certificate", fileObj.files[0], 'certificate.pem');
                disableBtn($this, true);
                $.ajax({
                    'url': 'sender.php',
                    'method': 'POST',
                    'data': formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                }).success(function(response) {
                    response = JSON.parse(response);
                    console.log(response);
                    showMessage(response.status ? response.message : response.error, response.status);
                    disableBtn($this, false);
                }).error(function() {
                    showMessage("Some thing went wrong!", false);
                    disableBtn($this, false);
                })
            });


            function showMessage(message, success) {
                let alert = $('.alert');

                alert.removeClass('alert-success');
                alert.removeClass('alert-danger');

                let currentClass = success ? 'alert-success': 'alert-danger';
                alert.addClass(currentClass);
                alert.text(message);
                alert.show();
            }

            function hideMessage() {
                $(".alert").hide();
            }

            function disableBtn(form, isDisable) {
                form.find('button').eq(0).prop('disabled', isDisable)
            }

        });



    </script>

</main>

<footer class="text-muted">
    <div class="container">
        <p class="float-right">
            <a href="#">Back to top</a>
        </p>
    </div>
</footer>

</body>
</html>