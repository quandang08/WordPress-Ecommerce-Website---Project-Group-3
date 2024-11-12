
document.addEventListener("DOMContentLoaded", function() {
    var snowflakeCount = snowfallSettings.count;
    var snowflakeSpeed = snowfallSettings.speed;
    var snowflakeColor = snowfallSettings.color;
    function createSnowflakes(snowflakeCount, snowflakeSpeed, snowflakeColor) {
        const container = document.querySelector('#snowfall-container'); 
        if(container) {
            for (var i = 0; i < snowflakeCount; i++) {
                var snowflake = document.createElement("div");
                var size = Math.random() * 5 + 2; // Kích thước ngẫu nhiên
                var duration = snowflakeSpeed * (Math.random() * 1.5 + 0.5); // Tốc độ ngẫu nhiên
                var left = Math.random() * 100; // Vị trí ngẫu nhiên
    
                snowflake.className = "snowflake";
                snowflake.style.position = "absolute";
                snowflake.style.width = size + "px";
                snowflake.style.height = size + "px";
                snowflake.style.left = left + "%";
                snowflake.style.backgroundColor = snowflakeColor;
                snowflake.style.borderRadius = "50%";
                snowflake.style.opacity = 0.9;
                snowflake.style.boxShadow = "0 0 12px rgba(255, 255, 255, 0.9)";
                snowflake.style.animation = `fall ${duration}s linear infinite`;
                // Thêm vào container
                container.appendChild(snowflake);
            }

        }
    }
    createSnowflakes(snowflakeCount, snowflakeSpeed, snowflakeColor);
    // Hiệu ứng rơi
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }
    `;
    document.head.appendChild(style);

    // Lắng nghe sự kiện mousemove để di chuyển tuyết khi chuột tới gần
    document.addEventListener('mousemove', function(e) {
        var snowflakes = document.querySelectorAll('.snowflake');

        snowflakes.forEach(function(snowflake) {
            var rect = snowflake.getBoundingClientRect(); // Lấy vị trí hạt tuyết
            var snowflakeX = rect.left + rect.width / 2;
            var snowflakeY = rect.top + rect.height / 2;

            var distanceX = snowflakeX - e.clientX;
            var distanceY = snowflakeY - e.clientY;

            var distance = Math.sqrt(distanceX * distanceX + distanceY * distanceY); // Tính khoảng cách giữa chuột và hạt tuyết

            var maxDistance = 1000; // Khoảng cách tối đa để tuyết "né" chuột
            if (distance < maxDistance) {
                // Tính toán hướng di chuyển của hạt tuyết
                var offsetX = distanceX / distance * 50; // Hạt tuyết di chuyển ra xa theo trục X
                var offsetY = distanceY / distance * 50; // Hạt tuyết di chuyển ra xa theo trục Y

                // Thêm vào chuyển động để hạt tuyết né ra xa
                snowflake.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
                snowflake.style.transition = 'transform 0.3s ease';
            } else {
                // Đặt lại vị trí hạt tuyết nếu không còn gần chuột
                snowflake.style.transform = 'translate(0, 0)';
            }
        });
    });
});
