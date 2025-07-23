export const redirect = (url) => {
    try {
        const token = JSON.parse(localStorage.getItem("authenticate"))?.access_token;
        if (token) {
            window.location.href = url;
        } else {
            window.location.href = url;
        }
    } catch (e) {
        console.error('Error parsing authentication data:', e);
        window.location.href = url;
    }
}

export const isLogin = async (requiredRole) => {
    const refreshCount = parseInt(localStorage.getItem("refresh") || "0");
    if (refreshCount >= 5) {
        localStorage.removeItem("refresh");
        localStorage.removeItem("authenticate");
        Logout();
        redirect('/login');
        return false;
    }

    try {
        const authData = JSON.parse(localStorage.getItem("authenticate"));
        if (!authData || !authData.access_token) {
            return false;
        }

        const user = await me();
        if (!user || !user.role) {
            return false;
        }

        if (Array.isArray(requiredRole)) {
            if (requiredRole.includes(user.role.name)) {
                return true;
            }else{
                LogoutWithoutAlert()
            }
        } else if (user.role.name === requiredRole) {
            return true;
        }

        if (document.referrer === window.location.href) {
            if (requiredRole !== "guest") {
                redirect('/dashboard');
            }
        } else if (document.referrer !== '') {
            window.history.back();
            localStorage.setItem("refresh", refreshCount + 1);
        } else if (authData && user.role.name !== 'Guest') {
            localStorage.setItem("refresh", refreshCount + 1);
            redirect('/dashboard');
        } else {
            redirect('/');
        }

        return false; // User is logged in but does not have the required role
    } catch (e) {
        console.error('Error parsing authentication data:', e);
        if (window.location.pathname !== "/login") {
            if (requiredRole === "Guest") {
                redirect('/');
            } else {
                redirect('/login');
            }
        }
        return false;
    }
};

export const getTokens = () => {
    const authData = JSON.parse(localStorage.getItem("authenticate"));
    if (!authData) {
        console.error('No authentication data found in localStorage.');
        return null;
    }
    return authData.access_token;
}

export const me = async () => {
    const authData = localStorage.getItem("authenticate");
    if (!authData) {
        console.error('No authentication data found in localStorage.');
        var listWithoutLogin = ["/","/forum","/tambahpesan","/kontak","/informasi","/pemetaan","/profil","/kontak"];
        if (!listWithoutLogin.includes(window.location.pathname) && window.location.pathname !== "/login" && window.location.pathname !== "/") {
            redirect('/login');
        }
        return null;
    }

    try {
        const parsedData = JSON.parse(authData);
        const response = await $.ajax({
            url: "/api/auth/me",
            method: "GET",
            headers: {
                "Authorization": "Bearer " + parsedData.access_token,
                "Accept": "Application/json"
            }
        });

        if (response.status === 401) {
            localStorage.removeItem("authenticate");
            redirect('/login');
            return null;
        }

        return response || parsedData.user || null;
    } catch (e) {
        console.error('Error parsing authentication data:', e);
        localStorage.removeItem("authenticate");
        redirect('/login');
        return null;
    }
};

export const SetTimeOut = () => { // logout 30 detik
    const authData = localStorage.getItem("authenticate");
    if (!authData) {
        return;
    }
    let timer;
    function resetTimer() {
        clearTimeout(window.timer);
        countdown();
        window.timer = setTimeout(() => {
            console.log('Logging out due to inactivity.');
            //LogoutWithoutAlert();
        }, 30000); // kalau udh 30 detik, bakal logout
    }

    function countdown() { // ini buat countdown waktu
        let time = 30;
        clearTimeout(timer);
        timer = setInterval(() => {
            if(time >= 0){
                console.log('Logging out in ' + time + ' seconds.');
                time--;
            }
        }, 1000);
    }

    window.addEventListener('mousemove', resetTimer, true);
    window.addEventListener('mousedown', resetTimer, true);
    window.addEventListener('keypress', resetTimer, true);
    window.addEventListener('touchstart', resetTimer, true);
    window.addEventListener('tounchmove', resetTimer, true);
    window.addEventListener('scroll', resetTimer, true);
    window.addEventListener('resize', resetTimer, true);
    window.addEventListener('click', resetTimer, true);
    window.addEventListener('keydown', resetTimer, true);
    window.addEventListener('wheel', resetTimer, true);
}

export const Logout = () => {
    Swal.fire({
        title: "Logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.isConfirmed) {
            const authData = JSON.parse(localStorage.getItem("authenticate"));
            $.ajax({
                url: '/api/auth/logout',
                type: "POST",
                headers: {
                    'Authorization': 'Bearer ' + authData.access_token
                },
                success: () => {
                    Swal.fire({
                        title: "Success",
                        text: "You have successfully logged out.",
                        icon: "success"
                    });
                    localStorage.removeItem("authenticate");
                    window.location.href = "/login";
                },
                error: (xhr, status, error) => {
                    Swal.fire({
                        title: "Fail",
                        text: "Failed to log out. Error: " + xhr.responseText,
                        icon: "error"
                    });
                }
            });
        }
    });
}

const LogoutWithoutAlert = () => {
    const authData = JSON.parse(localStorage.getItem("authenticate"));
    $.ajax({
        url: '/api/auth/logout',
        type: "POST",
        headers: {
            "Authorization": "Bearer " + authData.access_token
        },
        success: () => {
            localStorage.removeItem("authenticate");
            window.location.href = "/login";
        },
        error: (xhr, status, error) => {
            console.error('Failed to log out. Error:', xhr.responseText);
        }
    });
}
