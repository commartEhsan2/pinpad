jQuery(document).ready(function($) {
    let phoneNumber = '';
    let passwordMap = {
        '0': ['A', '@', 'F', 'N', 'O', 'h'],
        '1': ['P', 'a', '!', 'w', 'B', 'o'],
        '2': ['k', 'Y', '#', 'm', 'R', 'x'],
        '3': ['L', '$', 'n', 'G', 't', '&'],
        '4': ['%', 'r', 'j', 'C', 'I', 'c'],
        '5': ['D', 'v', 'f', 'H', 'z', 'E'],
        '6': ['?', 'e', 'K', 'p', 's', '^'],
        '7': ['x', 'V', 'b', 'U', 'g', 'J'],
        '8': ['Z', 'q', 'M', 'd', 'l', '*'],
        '9': ['y', 'T', 'i', 'W', 'X', 'u']
    };
    let passwordChars = {};
    let isLoginMode = true;

    // Error handling
    window.onerror = function(message, source, lineno, colno, error) {
        console.error(`Error: ${message} at ${source}:${lineno}:${colno}`, error);
        showMessage(`خطا: ${message} در فایل ${source}:${lineno}:${colno}`);
        return true; // Prevent the default browser error handling
    };

    $('#custom-login-button, #custom-register-button').click(function() {
        $('#commart-pinpad').show();
        setTimeout(() => {
            showMessage('شماره موبایل خود را وارد کنید.');
        }, 2000);
    });

    $('.keypad .num').click(function() {
        try {
            if (phoneNumber.length < 11) {
                phoneNumber += $(this).text();
                $(this).css("color", "#FFFFFF");
                setTimeout(() => {
                    $(this).css("color", "#727272");
                }, 200);
                updateDisplay();
            }
        } catch (err) {
            console.error("Error in keypad click handler:", err);
            showMessage("خطا در هنگام کلیک بر روی دکمه کیپد.");
        }
    });

    $('#backspace-button').click(function() {
        try {
            phoneNumber = phoneNumber.slice(0, -1);
            updateDisplay();
        } catch (err) {
            console.error("Error in backspace button handler:", err);
            showMessage("خطا در هنگام استفاده از دکمه بازگشت.");
        }
    });

    $('#swap-button').click(function() {
        try {
            isLoginMode = !isLoginMode;
            swapMode();
        } catch (err) {
            console.error("Error in swap button handler:", err);
            showMessage("خطا در هنگام تغییر حالت.");
        }
    });

    function swapMode() {
        try {
            $('#pinpad-title').text(isLoginMode ? 'ورود' : 'ثبت نام');
            $('#commart-pinpad').css('background-color', isLoginMode ? '#375ffa' : '#212121');
            $('#commart-pinpad').animate({ transform: 'scale(0.85)' }, 100, function() {
                $(this).animate({ transform: 'scale(1.10)' }, 100, function() {
                    $(this).animate({ transform: 'scale(1)' }, 100);
                });
            });
        } catch (err) {
            console.error("Error in swapMode function:", err);
            showMessage("خطا در هنگام تغییر حالت.");
        }
    }

    function updateDisplay() {
        try {
            $('#number-display').text(phoneNumber || '\xa0'); // Non-breaking space if empty
            $('#number-display').css('opacity', 0.5);
            setTimeout(() => {
                $('#number-display').css('opacity', 1);
            }, 200);

            const usernameField = document.querySelector('#rm_login_form_1-element-1');
            if (usernameField) {
                usernameField.value = phoneNumber;
            }

            const passwordField = document.querySelector('#rm_login_form_1-element-2');
            if (passwordField) {
                passwordField.value = generatePassword(phoneNumber);
            }

            if (phoneNumber.length === 11) {
                if (phoneNumber.startsWith('09')) {
                    if (isLoginMode) {
                        ajaxLoginCheck(phoneNumber, passwordField.value);
                    } else {
                        ajaxRegisterCheck(phoneNumber, passwordField.value);
                    }
                } else {
                    triggerWrongAnimation();
                    showMessage('شماره معتبر نیست.', 3000);
                }
            }
        } catch (err) {
            console.error("Error in updateDisplay function:", err);
            showMessage("خطا در هنگام به‌روزرسانی نمایشگر.");
        }
    }

    function generatePassword(phoneNumber) {
        try {
            let password = '';
            let digitCounts = {};

            for (let i = 0; i < phoneNumber.length; i++) {
                const digit = phoneNumber[i];
                if (!digitCounts[digit]) {
                    digitCounts[digit] = 0;
                }
                const charIndex = digitCounts[digit] % passwordMap[digit].length;
                password += passwordMap[digit][charIndex];
                digitCounts[digit]++;
            }
            return password;
        } catch (err) {
            console.error("Error in generatePassword function:", err);
            showMessage("خطا در هنگام تولید رمز عبور.");
            return '';
        }
    }

    function ajaxLoginCheck(username, password) {
        try {
            $.ajax({
                type: 'POST',
                url: commartPinpad.ajax_url,
                data: {
                    action: 'custom_ajax_login',
                    username: username,
                    password: password,
                },
                success: function(response) {
                    if (response.success) {
                        $('#number-display').addClass('correct');
                        setTimeout(() => {
                            $('#number-display').removeClass('correct');
                            $('#login-form form').submit();
                        }, 1000);
                    } else {
                        triggerWrongAnimation();
                        showMessage('شماره موجود نیست. ثبت نام کنید.', 3000);
                    }
                }
            });
        } catch (err) {
            console.error("Error in ajaxLoginCheck function:", err);
            showMessage("خطا در هنگام بررسی ورود.");
        }
    }

    function ajaxRegisterCheck(username, password) {
        try {
            $.ajax({
                type: 'POST',
                url: commartPinpad.ajax_url,
                data: {
                    action: 'custom_ajax_register',
                    username: username,
                    password: password,
                },
                success: function(response) {
                    if (response.success) {
                        $('#number-display').addClass('correct');
                        setTimeout(() => {
                            $('#number-display').removeClass('correct');
                            $('#register-form form').submit();
                        }, 1000);
                    } else {
                        triggerWrongAnimation();
                        showMessage('شماره موجود است. لطفا وارد شوید.', 3000);
                    }
                }
            });
        } catch (err) {
            console.error("Error in ajaxRegisterCheck function:", err);
            showMessage("خطا در هنگام بررسی ثبت نام.");
        }
    }

    function triggerWrongAnimation() {
        try {
            $('#number-display').removeClass('ajax-wait').addClass('wrong');
            $('#commart-pinpad').addClass('wrong-bg');
            setTimeout(() => {
                $('#number-display').removeClass('wrong');
                $('#commart-pinpad').removeClass('wrong-bg');
                removeDigitsOneByOne();
            }, 1000);
        } catch (err) {
            console.error("Error in triggerWrongAnimation function:", err);
            showMessage("خطا در هنگام اجرای انیمیشن خطا.");
        }
    }

    function removeDigitsOneByOne() {
        try {
            if (phoneNumber.length > 0) {
                phoneNumber = phoneNumber.slice(0, -1);
                updateDisplay();
                setTimeout(removeDigitsOneByOne, 100);
            }
        } catch (err) {
            console.error("Error in removeDigitsOneByOne function:", err);
            showMessage("خطا در هنگام حذف ارقام یک به یک.");
        }
    }

    function showMessage(text, duration = 3000) {
        try {
            clearTimeout($('#message')[0].timeout);
            $('#message').text(text).addClass('visible');
            $('#message')[0].timeout = setTimeout(() => {
                $('#message').removeClass('visible');
            }, duration);
        } catch (err) {
            console.error("Error in showMessage function:", err);
        }
    }
});
