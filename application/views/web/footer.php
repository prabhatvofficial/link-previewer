</div>
<footer>
<div class="bg-dark">
    <div class="container">
        <ul class="d-flex flex-wrap justify-content-center m-0 p-2" style="list-style:none;">
            
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('privacy');?>">Privacy</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('about');?>">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('mostcheacked');?>">Most Checked</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('mostdiscussed');?>">Most Discussed</a>
            </li>
            
        </ul>
    </div>
</div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // $(document).ready(function() {  
    //     const link = document.querySelector('#whatisWebsitemeta');
    //     let data_url = link.getAttribute('data-url');
    //     let data_id = link.getAttribute('data-target-id');
    //     $.ajax({
    //         url: data_url,
    //         type: "GET",
    //         beforeSend: function() {
    //             $(".loaderDiv").show();
    //             $("#" + data_id).html("<img src='https://iswebupordown.com/assets/loading-buffering.gif' style='width:50px' />");
    //         },
    //         success: function(data) {
    //             $(data).each(function(index, el) {
    //                 $(".loaderDiv").hide();
    //                 $("#" + data_id).html(data);
    //             });
    //         }
    //     })
    // });
</script>
</body>
</html>