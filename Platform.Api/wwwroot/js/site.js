function closeModalOnSuccess(event) {
    // Close modal only if the request was successful (status 200-299)
    if (event.detail.xhr.status >= 200 && event.detail.xhr.status < 300) {
        document.getElementById('modal-form')?.close();
    }
}

function openDeleteDialog(model, id) {
    const d = document.getElementById('confirm-delete');
    d.showModal();

    const yes = document.getElementById('confirm-yes');
    yes.onclick = () => {
        htmx.ajax('DELETE', `/api/data/${model}/${id}`, {
            target: `tr[data-id="${id}"]`,
            swap: 'outerHTML'
        });
        d.close();
    };
}

document.getElementById('confirm-no')?.addEventListener('click', () => {
    document.getElementById('confirm-delete')?.close();
});

function showErrorDialog(errorMessage) {
    const errorDialog = document.getElementById('error-dialog');
    const errorMessageEl = document.getElementById('error-message');
    errorMessageEl.textContent = errorMessage;
    errorDialog.showModal();
}

htmx.on("htmx:responseError", (evt) => {
    let errorMessage = "Unknown error";
    
    try {
        const response = JSON.parse(evt.detail.xhr.response);
        errorMessage = response.error || response.message || JSON.stringify(response);
    } catch (e) {
        errorMessage = evt.detail.xhr.responseText || evt.detail.xhr.statusText || "Unknown error";
    }
    
    showErrorDialog(errorMessage);
});

htmx.on("htmx:sendError", (evt) => {
    let errorMessage = "Failed to send request";
    
    try {
        const response = JSON.parse(evt.detail.xhr.response);
        errorMessage = response.error || response.message || JSON.stringify(response);
    } catch (e) {
        errorMessage = evt.detail.xhr.responseText || "Failed to send request";
    }
    
    showErrorDialog(errorMessage);
});

document.body.addEventListener('htmx:configRequest', (event) => {
    const token = document.getElementById('csrf-token')?.value;
    if (token) {
        event.detail.headers['X-CSRF-TOKEN'] = token;
    }
    
    // 获取当前URL中的语言参数并添加到HTMX请求中
    const urlParams = new URLSearchParams(window.location.search);
    const lang = urlParams.get('lang');
    if (lang) {
        // 添加语言参数到请求参数中
        if (!event.detail.parameters) {
            event.detail.parameters = {};
        }
        event.detail.parameters['lang'] = lang;
    }
});
