$(document).ready(function() {
    $(function() {
        setModalContent('Initializing System', '<div class="text-center"><span class="fa fa-spinner fa-4x fa-pulse"></span><h4 class="no-margin gap-top">Please Wait...</h4></div>', '');
        openModal('static');

        $.ajax({
            url: '/data/initialize',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function(response) {
                createChart('statistics-chart', 'line', {
                    labels: ["January", "February", "March", "April", "May", "June", "July"],
                    datasets: [{
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(75,192,192,0.4)",
                        borderColor: "rgba(75,192,192,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: [65, 59, 80, 81, 56, 55, 40],
                        spanGaps: false,
                    }]
                });

                setModalContent('Initializing System', response['message'], '');

                setTimeout(function() {
                    closeModal();
                }, 2000);
            }
        });

        return false;
    });
});