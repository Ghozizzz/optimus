/* 
 *  Allright Reserved (c) 2015. All rights reserved by http://nextsolusindo.com
 *  =================================================================================
 *
 *  =================================================================================
 *  Change Log:
 *  Date                Author          Version     Request     Comment
 *  5/2/2015            Yung Fei                                Initial Creator 
 * 
 *  =================================================================================
 *  Allright Reserved (c) 2015. All rights reserved by http://nextsolusindo.com
 * 
 */

/**
 * 
 * @param {type} ID
 * @param {type} primaryField
 * @param {type} data
 * @returns {Boolean}
 * 
 */

function deactive(ID, primaryField, data, url) {
    var reply = confirm('Anda yakin akan mendeaktif data ini?')
    if (reply === true) {
        var urlPaging = url;
        var requestData = {
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'deactive'
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            before: function(response){

            },
            success: function(response) {
                
            },complete: function(response){
                if (response.responseText === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

/**
 * 
 * @param {type} ID
 * @param {type} primaryField
 * @param {type} data
 * @returns {Boolean}
 * 
 */
function active(ID, primaryField, data, url) {
    var reply = confirm('Anda akan mengaktifkan data ini?')
    if (reply === true) {

        var urlPaging = url;
        var requestData = {
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'active'
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            before: function(response){

            },
            success: function(response) {
                
            },complete: function(response){
                if (response.responseText === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

/**
 * 
 * @param {type} ID
 * @param {type} primaryField
 * @param {type} data
 * @returns {Boolean}
 * 
 */
function deleteData(ID, primaryField, data,url) {
    var reply = confirm('Anda yakin akan menghapus data ini?')
    if (reply === true) {

        var urlPaging = url;
        var requestData = {
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'delete'
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                
            },complete: function(response) {
                if (response.responseText === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

/**
 * 
 * @param {type} ID
 * @param {type} primaryField
 * @param {type} data
 * @returns {Boolean}
 * 
 */
function deleteSoftData(ID, primaryField, data, url) {
    var reply = confirm('Anda yakin akan menghapus data ini?')
    if (reply === true) {

        var urlPaging = url;
        var requestData = {            
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'deleteSoft'
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            complete: function(response) {
                if (response.responseText === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        console.log('masuk');
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

function deleteItemWithPicture(ID, primaryField, data,url, pictureUrl) {
    var reply = confirm('Anda yakin akan menghapus data ini?')
    if (reply === true) {

        var urlPaging = url;
        var requestData = {
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'delete',
            'pictureUrl' : pictureUrl
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                
            },complete: function(response) {
                if (response.responseText === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

function deleteTransactionLine(ID, primaryField, data, url) {
    var reply = confirm('Are you sure want to delete this record?')
    if (reply === true) {

        var urlPaging = url;
        var requestData = {
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'deleteTransactionLine'
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

function deletePaymentLine(ID, primaryField, data,paymentID) {
    var reply = confirm('Are you sure want to delete this record?')
    if (reply === true) {

        var urlPaging = "deactive.php";
        var requestData = {
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'deletePaymentLine',
            'paymentID' : paymentID,
        };

        $.ajax({
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response.message === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                    $("#totalPayment").val(response.total_payment);
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}


function payCommision(ID, primaryField, data,url) {
    var reply = confirm('Anda akan melakukan pembayaran komisi ini?')
    if (reply === true) {

        var urlPaging = url;
        var requestData = {
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'paid'
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                
            },complete: function(response) {
                if (response.responseText === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

function markCoupon(ID, primaryField, data,url) {
    var reply = confirm('Anda akan memenggunakan kupon ini?')
    if (reply === true) {

        var urlPaging = url;
        var requestData = {
            'ID': ID,
            'data': data,
            'primaryField' : primaryField,
            'action': 'used'
        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: requestData,
            url: urlPaging,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                
            },complete: function(response) {
                if (response.responseText === "success")
                {
                    if (typeof (refreshDiv) === "function") {
                        refreshDiv();
                    }else{
                        location.reload(true);
                    }
                } else {
                    alert('Error');
                }
            }
        });

        return true;
    }
    return false;
}

function showPopup(menu,width,height){  
        
        var left = parseInt((screen.availWidth/2) - (width/2));
        var top = parseInt((screen.availHeight/2) - (height/2));
    
        var windowObjectReference = window.open(menu, '_blank','left=' + left + ',top=' + top + 'screenX=' + left + ',screenY=' + top +',\n\
            width='+width+',height='+height+',location=no,status=no,menubar=no,toolbar=no,resizable=no,scrollbars=yes');
        windowObjectReference.focus();        
        
    }
    
    
    