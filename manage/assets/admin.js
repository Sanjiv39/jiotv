$("#userCapv").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        do_login();
    }
});
function do_login()
{
    $("#iforalert").fadeOut();
    $("#usrLoginBtn").text(" Please Wait .. ");
    let userId = $("#userId").val();
    let password = $("#userPass").val();
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=login&username=" + userId + "&password=" + password,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
            $("#usrLoginBtn").text(" LOGIN ");
			try { data = JSON.parse(data); }catch(err){}
			if(data.message !== null && data.message !== null && data.message !== undefined)
			{
				$("#iforalert").text(data.message);
				$("#iforalert").fadeIn();
			}
			else
			{
				$("#iforalert").text("Something Went Wrong");
				$("#iforalert").fadeIn();
			}
			if(data.status == "success")
			{
				window.setTimeout(function(){
					window.location = "dashboard.php";
				}, 2200);
			}
        },
        "error": function(data)
        {
            $("#usrLoginBtn").text(" LOGIN ");
			$("#iforalert").text("Network Failed or Internal Server Error");
			$("#iforalert").fadeIn();
        }
    });
}
function check_login_session()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=login_session",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
				window.location = "dashboard.php";
			}
        },
        "error": function(data)
        {
        }
    });
}

function doUserlogout()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=logout",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			window.location = "index.php";
        },
        "error": function(data)
        {
            window.location = "index.php";
        }
    });
}

function check_if_admin_loggedIn()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=login_session",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
				if($("body").is(":visible")){}else{$("body").fadeIn();}
			}
            else
            {
                doUserlogout();
            }
        },
        "error": function(data)
        {
            doUserlogout();
        }
    });
}

function dashboard_data()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=dashboard_data",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
				$("#usf_admin_username").val(data.data.login.username);
                $("#usf_admin_password").val(data.data.login.password);
                $("#webASts").text(data.data.web_app_status);
			}
        },
        "error": function(data)
        {
           
        }
    });
}

$("#usf_admin_updbtn").on("click", update_admin_credentials);
$("#usf_admin_password").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        update_admin_credentials();
    }
});

function update_admin_credentials()
{
    $("#usf_admin_updbtnholder").html(loaderhtml());
    let username = $("#usf_admin_username").val();
    let password = $("#usf_admin_password").val();
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=update_credentials&username=" + username + "&password=" + password,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#usf_admin_alert").attr("class", "alert alert-success mb-3");
				$("#usf_admin_alert").text(data.message);
                window.setTimeout(function(){
                    doUserlogout();
                }, 1600);
			}
            else
            {
                $("#usf_admin_alert").attr("class", "alert alert-secondary mb-3");
                if(data.status == "error")
                {
                    $("#usf_admin_alert").text(data.message);
                }
                else
                {
                    $("#usf_admin_alert").text("Something Went Wrong");
                }
            }
            $("#usf_admin_updbtnholder").html('<button class="btn btn-primary btn-sm usfadminbtn" onclick="update_admin_credentials()"> Update Credentials </button>');
            $("#usf_admin_alert").fadeIn();
        },
        "error": function(data)
        {
            $("#usf_admin_alert").attr("class", "alert alert-secondary mb-3");
            $("#usf_admin_alert").text("Network Error or Server Failed");
            $("#usf_admin_alert").fadeIn();
            $("#usf_admin_updbtnholder").html('<button class="btn btn-primary btn-sm usfadminbtn" onclick="update_admin_credentials()"> Update Credentials </button>');
        }
    });
}

function loaderhtml()
{
    let imgpath = "../assets/apploader.svg";
    let imghtml = '<img src="' + imgpath + '" alt="Loading..." width="32" height="32"/>';
    return imghtml;
}

function app_login_data()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=app_login_data",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
				if(data.data.app_login_status == true || data.data.app_login_status == "true")
                {
                    $("#jio_login_box_1").fadeOut();
                    $("#jio_login_box_2").fadeOut();
                }
                if(data.data.app_login_status == false)
                {
                    $("#cBOUwevpwe9U897gcbqw").append('<li class="list-group-item jionews_yo_alert">Please Login To JioTV Account</li>');
                }
                else
                {
                    $("#lg_jio").fadeIn();
                }
                if(data.data.app_login_status == true && data.data.app_login_method == "otp")
                {
                    $("#updz_jtv").fadeIn();
                    $("#cBOUwevpwe9U897gcbqw").append('<li class="list-group-item jio_green_alert">JioTV Logged In Using OTP Method</li>');
                }
                if(data.data.app_login_status == true && data.data.app_login_method == "nonotp")
                {
                    $("#cBOUwevpwe9U897gcbqw").append('<li class="list-group-item jio_green_alert">JioTV Logged In Using Non-OTP Method</li>');
                    if(data.data.voot_login_status == false && data.data.jionews_login_status == false)
                    {
                        $("#cBOUwevpwe9U897gcbqw").append('<li class="list-group-item jio_warning_alert">It Is Adviced To Login In JioNews or Voot To Enable  Backup Playback Using Non-Otp Method Properly</li>');
                    }
                }
                if(data.data.voot_login_status == true)
                {
                    $("#updz_vot").fadeIn();
                    $("#lg_voot").fadeIn();
                    $("#voot_login_box").fadeOut();
                    $("#cBOUwevpwe9U897gcbqw").append('<li class="list-group-item voot_yo_alert">Voot Logged In</li>');
                }
                if(data.data.jionews_login_status == true)
                {
                    $("#updz_jnw").fadeIn();
                    $("#lg_jionews").fadeIn();
                    $("#jio_login_box_3").fadeOut();
                    $("#cBOUwevpwe9U897gcbqw").append('<li class="list-group-item jionews_yo_alert">JioNews Logged In</li>');
                }
			}
        },
        "error": function(data)
        {
           
        }
    });
}

$("#jio_otp_login_mobile").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        do_jiootp_login();
    }
});

function do_jiootp_login()
{
    $("#jio_otp_login_btn_holder").html(loaderhtml());
    let jio_number = $("#jio_otp_login_mobile").val();
    let jio_otp = $("#jio_otp_login_otp").val();
    
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=jio_otp_login&mobile=" + jio_number + "&otp=" + jio_otp,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#jio_otp_login_alert").attr("class", "alert alert-success mb-3");
				if($("#jio_otp_login_otp_holder").is(":visible"))
                {
                    $("#jio_otp_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_otp_login_btn" onclick="do_jiootp_login()"> Verify OTP </button>');
                    window.location.reload();
                }
                else
                {
                    $("#jio_otp_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_otp_login_btn" onclick="do_jiootp_login()"> Verify OTP </button>');
                    $("#jio_otp_login_otp_holder").fadeIn();
                }
                $("#jio_otp_login_alert").text(data.message);
			}
            else
            {
                $("#jio_otp_login_alert").attr("class", "alert alert-danger mb-3");
                $("#jio_otp_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_otp_login_btn" onclick="do_jiootp_login()"> Receive OTP </button>');
                if(data.status == "error")
                {
                    $("#jio_otp_login_alert").text(data.message);
                }
                else
                {
                    $("#jio_otp_login_alert").text("Something Went Wrong");
                }
                $("#jio_otp_login_alert").fadeIn();
            }
        },
        "error": function(data)
        {
            $("#jio_otp_login_alert").text("Server Error or Network Failed");
            $("#jio_otp_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_otp_login_btn" onclick="do_jiootp_login()"> Receive OTP </button>');
            $("#jio_otp_login_alert").fadeIn();
        }
    });
}

function do_jiononotp_login()
{
    $("#jio_nonotp_login_btn_holder").html(loaderhtml());
    let jio_number = $("#jio_nonotp_login_mobile").val();
    let jio_password = $("#jio_nonotp_login_password").val();
    
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=jio_nonotp_login&mobile=" + jio_number + "&password=" + jio_password,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#jio_nonotp_login_alert").attr("class", "alert alert-success mb-3");
				$("#jio_nonotp_login_alert").text(data.message);
                $("#jio_nonotp_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_nonotp_login_btn" onclick="do_jiononotp_login()"> Login Now </button>');
                window.location.reload();
            }
            else
            {
                $("#jio_nonotp_login_alert").attr("class", "alert alert-danger mb-3");
                $("#jio_nonotp_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_nonotp_login_btn" onclick="do_jiononotp_login()"> Login Now </button>');
                if(data.status == "error")
                {
                    $("#jio_nonotp_login_alert").text(data.message);
                }
                else
                {
                    $("#jio_nonotp_login_alert").text("Something Went Wrong");
                }
            }
            $("#jio_nonotp_login_alert").fadeIn();
        },
        "error": function(data)
        {
            $("#jio_nonotp_login_alert").text("Server Error or Network Failed");
            $("#jio_nonotp_login_alert").fadeIn();
            $("#jio_nonotp_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_nonotp_login_btn" onclick="do_jiononotp_login()"> Login Now </button>');
        }
    });
}

$("#jionews_login_mobile").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        do_jionews_login();
    }
});

$("#jionews_login_otp").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        do_jionews_login();
    }
});

function do_jionews_login()
{
    $("#jionews_login_btn_holder").html(loaderhtml());
    let jio_number = $("#jionews_login_mobile").val();
    let jio_password = $("#jionews_login_otp").val();
    
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=jio_news_login&mobile=" + jio_number + "&otp=" + jio_password,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#jionews_login_alert").attr("class", "alert alert-success mb-3");
				$("#jionews_login_alert").text(data.message);
                if($("#jionews_login_otpholder").is(":visible"))
                {
                    window.setTimeout(function(){
                        window.location.reload();
                    }, 1600);
                }
                else
                {
                    $("#jionews_login_otpholder").fadeIn();
                }
                $("#jionews_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jionews_login_btn" onclick="do_jionews_login()"> Verify OTP </button>');
			}
            else
            {
                $("#jionews_login_alert").attr("class", "alert alert-danger mb-3");
                $("#jionews_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jionews_login_btn" onclick="do_jionews_login()"> Receive OTP </button>');
                if(data.status == "error")
                {
                    $("#jionews_login_alert").text(data.message);
                }
                else
                {
                    $("#jionews_login_alert").text("Something Went Wrong");
                }
            }
            $("#jionews_login_alert").fadeIn();
        },
        "error": function(data)
        {
            $("#jionews_login_alert").text("Server Error or Network Failed");
            $("#jionews_login_alert").fadeIn();
            $("#jionews_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jionews_login_btn" onclick="do_jionews_login()"> Receive OTP </button>');
        }
    });
}

function do_voot_login()
{
    $("#voot_login_btn_holder").html(loaderhtml());
    let identifier = $("#voot_login_mobile").val();
    let password = $("#voot_login_password").val();
    
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=voot_login&identifier=" + identifier + "&password=" + password,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#voot_login_alert").attr("class", "alert alert-success mb-3");
				$("#voot_login_alert").text(data.message);
                window.location.reload();
			}
            else
            {
                $("#voot_login_alert").attr("class", "alert alert-danger mb-3");
                if(data.status == "error")
                {
                    $("#voot_login_alert").text(data.message);
                }
                else
                {
                    $("#voot_login_alert").text("Something Went Wrong");
                }
            }
            $("#voot_login_alert").fadeIn();
            $("#voot_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="voot_login_btn" onclick="do_voot_login()"> Login Now </button>');
        },
        "error": function(data)
        {
            $("#voot_login_alert").text("Server Error or Network Failed");
            $("#voot_login_alert").fadeIn();
            $("#voot_login_btn_holder").html('<button class="btn btn-primary btn-sm usfadminbtn" id="jio_nonotp_login_btn" onclick="do_jiononotp_login()"> Login Now </button>');
        }
    });
}

$("#lg_jio").on("click", function(){
    logout_helper_app("jio");
});

$("#lg_jionews").on("click", function(){
    logout_helper_app("jionews");
});

$("#lg_voot").on("click", function(){
    logout_helper_app("voot");
});

function logout_helper_app(appname)
{
    let action = "action=logout_" + appname;
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": action,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                window.location.reload();
			}
            else
            {
                if(data.status == "error")
                {
                    alert(data.message);
                }
                else
                {
                    alert("Something Went Wrong");
                }
            }
        },
        "error": function(data)
        {
            alert("Server Error or Network Failed");
        }
    });
}

function stream_data()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=stream_data",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#wwd_proxy").val(data.data.worldwide_proxy);
                $("#server_bypass_method").val(data.data.server_bypass_method);
                $("#res_ua").val(data.data.useragent_check);
                $("#res_origin").val(data.data.origin_check);
                $("#res_referer").val(data.data.referer_check);

                $("#stream_token_status").val(data.data.stream_token.status);
                $("#stream_token_ip_restriction").val(data.data.stream_token.ip_check);
                $("#stream_token_validity").val(data.data.stream_token.validity);

                $("#setStreamModeCurrent").text(data.data.current_stream_mode);

                $("#dpapiStatus").text(data.data.direct_play.status);
                $("#dpapiExmLink").text(data.data.direct_play.link);
            }
        },
        "error":function(data)
        {

        }
    });
}

function update_server_bypass_method()
{
    $("#server_bypass_method_btholder").html(loaderhtml());
    let bypass_method = $("#server_bypass_method").val();
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=update_server_bypass_method&value=" + bypass_method,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#server_bypass_method_alert").attr("class", "alert alert-success");
                $("#server_bypass_method_alert").text(data.message);
                stream_data();
            }
            else
            {
                $("#server_bypass_method_alert").attr("class", "alert alert-warning");
                if(data.status == "error")
                {
                    $("#server_bypass_method_alert").text(data.message);
                }
                else
                {
                    $("#server_bypass_method_alert").text("Something Went Wrong");
                }
            }
            $("#server_bypass_method_alert").fadeIn();
            $("#server_bypass_method_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="server_bypass_method_btn" onclick="update_server_bypass_method()"> Update </button>');
        },
        "error":function(data)
        {
            $("#server_bypass_method_alert").attr("class", "alert alert-warning");
            $("#server_bypass_method_alert").text("Network Failer or Server Error Occured");
            $("#server_bypass_method_alert").fadeIn();
            $("#server_bypass_method_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="server_bypass_method_btn" onclick="update_server_bypass_method()"> Update </button>');
        }
    });
}

function update_wwd_proxy()
{
    $("#wwd_proxy_btholder").html(loaderhtml());
    let bypass_method = $("#wwd_proxy").val();
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=update_wwd_proxy&value=" + bypass_method,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#wwd_proxy_alert").attr("class", "alert alert-success");
                $("#wwd_proxy_alert").text(data.message);
                stream_data();
            }
            else
            {
                $("#wwd_proxy_alert").attr("class", "alert alert-warning");
                if(data.status == "error")
                {
                    $("#wwd_proxy_alert").text(data.message);
                }
                else
                {
                    $("#wwd_proxy_alert").text("Something Went Wrong");
                }
            }
            $("#wwd_proxy_alert").fadeIn();
            $("#wwd_proxy_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="wwd_proxy_btn" onclick="update_wwd_proxy()"> Update </button>');
        },
        "error":function(data)
        {
            $("#wwd_proxy_alert").attr("class", "alert alert-warning");
            $("#wwd_proxy_alert").text("Network Failer or Server Error Occured");
            $("#wwd_proxy_alert").fadeIn();
            $("#wwd_proxy_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="wwd_proxy_btn" onclick="update_wwd_proxy()"> Update </button>');
        }
    });
}

function update_all_stres()
{
    $("#update_res_btholder").html(loaderhtml());
    let stm_ua = $("#res_ua").val();
    let stm_origin = $("#res_origin").val();
    let stm_referer = $("#res_referer").val();
    let stheaval = "ua=" + stm_ua + "&origin=" + stm_origin + "&referer=" + stm_referer;
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=update_stream_restriction&" + stheaval,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#res_method_alert").attr("class", "alert alert-success");
                $("#res_method_alert").text(data.message);
                stream_data();
            }
            else
            {
                $("#res_method_alert").attr("class", "alert alert-warning");
                if(data.status == "error")
                {
                    $("#res_method_alert").text(data.message);
                }
                else
                {
                    $("#res_method_alert").text("Something Went Wrong");
                }
            }
            $("#res_method_alert").fadeIn();
            $("#update_res_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="update_res_btn" onclick="update_all_stres()"> Update </button>');
        },
        "error":function(data)
        {
            $("#res_method_alert").attr("class", "alert alert-warning");
            $("#res_method_alert").text("Network Failer or Server Error Occured");
            $("#res_method_alert").fadeIn();
            $("#update_res_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="update_res_btn" onclick="update_all_stres()"> Update </button>');
        }
    });
}

function update_stream_tokenata()
{
    $("#stream_token_update_btholder").html(loaderhtml());
    let strm_status = $("#stream_token_status").val();
    let strm_ip = $("#stream_token_ip_restriction").val();
    let strm_validity = $("#stream_token_validity").val();
    let stheaval = "status=" + strm_status + "&ipres=" + strm_ip + "&validity=" + strm_validity;
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=update_stream_token_restriction&" + stheaval,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#stream_token_sec_alert").attr("class", "alert alert-success");
                $("#stream_token_sec_alert").text(data.message);
                stream_data();
            }
            else
            {
                $("#stream_token_sec_alert").attr("class", "alert alert-warning");
                if(data.status == "error")
                {
                    $("#stream_token_sec_alert").text(data.message);
                }
                else
                {
                    $("#stream_token_sec_alert").text("Something Went Wrong");
                }
            }
            $("#stream_token_sec_alert").fadeIn();
            $("#stream_token_update_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="stream_token_update_btn" onclick="update_stream_tokenata()"> Update </button>');
        },
        "error":function(data)
        {
            $("#stream_token_sec_alert").attr("class", "alert alert-warning");
            $("#stream_token_sec_alert").text("Network Failer or Server Error Occured");
            $("#stream_token_sec_alert").fadeIn();
            $("#stream_token_update_btholder").html('<button class="btn btn-info usfadminbtn btn-sm" id="stream_token_update_btn" onclick="update_stream_tokenata()"> Update </button>');
        }
    });
}

function updatezone(type)
{
    $("#update_zone_alert").attr("class", "alert alert-warning");
    $("#update_zone_alert").text("Please Wait ...");
    $("#update_zone_alert").show();

    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=update_zone&type=" + type,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#update_zone_alert").attr("class", "alert alert-success");
                $("#update_zone_alert").text(data.message);
                stream_data();
            }
            else
            {
                $("#update_zone_alert").attr("class", "alert alert-danger");
                if(data.status == "error")
                {
                    $("#update_zone_alert").text(data.message);
                }
                else
                {
                    $("#update_zone_alert").text("Something Went Wrong");
                }
            }
        },
        "error":function(data)
        {
            $("#update_zone_alert").attr("class", "alert alert-danger");
            $("#update_zone_alert").text("Network Failer or Server Error Occured");
        }
    });
}

function changeDirectPlayAPIStatus()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=update_direct_play_status",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                stream_data();
            }
            else
            {
                let err_msg = "";
                if(data.status == "error")
                {
                    err_msg = data.message;
                }
                else
                {
                    err_msg = "Something Went Wrong";
                }
                alert(err_msg);
            }
        },
        "error":function(data)
        {
            let err_msg = "Network Failed or Server Error Occured";
            alert(err_msg);
        }
    });
}

function change_webApp_Status()
{
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=change_Webapp_Status",
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                dashboard_data();
            }
            else
            {
                let err_msg = "";
                if(data.status == "error")
                {
                    err_msg = data.message;
                }
                else
                {
                    err_msg = "Something Went Wrong";
                }
                alert(err_msg);
            }
        },
        "error":function(data)
        {
            let err_msg = "Network Failed or Server Error Occured";
            alert(err_msg);
        }
    });
}

function generateIPTVPlaylistLink()
{
    $("#ipdpholder").html(loaderhtml());
    let link_type = $("#playlist_link_type").val();
    let link_validity = $("#playlist_link_validity").val();
    let playlist_validity = $("#playlist_mainlink_validity").val();
    $.ajax({
        "url" : "../app/adminapi.php",
        "type": "POST",
        "data": "action=genIPTVPlaylist&type=" + link_type + "&validity=" + link_validity + "&playlist_validity=" + playlist_validity,
        "beforeSend": function(xhr)
        {

        },
        "success": function(data)
        {
			try { data = JSON.parse(data); }catch(err){}
			if(data.status == "success")
			{
                $("#nwIPYLink").val(data.data);
                $("#iptvlinkmodal").modal("show");
                stream_data();
            }
            else
            {
                let err_msg = "";
                if(data.status == "error")
                {
                    err_msg = data.message;
                }
                else
                {
                    err_msg = "Something Went Wrong";
                }
                alert(err_msg);
            }
            $("#ipdpholder").html('<button class="btn btn-info usfadminbtn btn-sm" onclick="generateIPTVPlaylistLink()"> Generate Now </button>');
        },
        "error":function(data)
        {
            let err_msg = "Network Failed or Server Error Occured";
            $("#ipdpholder").html('<button class="btn btn-info usfadminbtn btn-sm" onclick="generateIPTVPlaylistLink()"> Generate Now </button>');
        }
    });
}

function copy_nwIPYLink_CB()
{
    let inputElement = document.getElementById("nwIPYLink");
    inputElement.select();
    document.execCommand("copy");
    inputElement.blur();
    navigator.clipboard.writeText($("#nwIPYLink").val());
    alert("Copied To Clipbaord");
}