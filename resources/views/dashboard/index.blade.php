<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard IoT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --danger: #f72585;
            --success: #4cc9f0;
            --warning: #f8961e;
            --dark: #212529;
            --light: #f8f9fa;
            --gray: #6c757d;
            --white: #ffffff;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }

        #container {
            min-height: 100vh;
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .row {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1;
            min-width: 200px;
            padding: 25px;
            background-color: var(--white);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border-top: 4px solid var(--primary);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray);
        }

        .card-header i {
            font-size: 1.2rem;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 600;
        }

        .text-suhu {
            color: var(--danger);
        }

        .text-kelembapan {
            color: var(--success);
        }

        .text-posisi-servo {
            color: var(--accent);
        }

        #input-lcd {
            width: 100%;
            outline: 0;
            border: 1px solid #e0e0e0;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        #input-lcd:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.2);
        }

        #btn-submit {
            width: 100%;
            outline: 0;
            border: 0;
            background: var(--primary);
            color: white;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
        }

        #btn-submit:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .card-table {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            padding: 15px;
            background: var(--primary);
            color: white;
            font-weight: 500;
            text-align: left;
        }

        td {
            padding: 15px;
            background: var(--white);
            border-bottom: 1px solid #f0f0f0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8f9fa;
        }

        .status-online {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #28a745;
            font-weight: 500;
        }

        .status-online::before {
            content: "";
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #28a745;
        }

        .status-offline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #dc3545;
            font-weight: 500;
        }

        .status-offline::before {
            content: "";
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #dc3545;
        }

        #servo-slider {
            width: 100%;
            height: 8px;
            -webkit-appearance: none;
            appearance: none;
            background: #e0e0e0;
            border-radius: 10px;
            outline: none;
            opacity: 0.7;
            transition: var(--transition);
        }

        #servo-slider:hover {
            opacity: 1;
        }

        #servo-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            transition: var(--transition);
        }

        #servo-slider::-webkit-slider-thumb:hover {
            transform: scale(1.1);
            background: var(--secondary);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray);
        }

        @media only screen and (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .card {
                width: 100%;
            }

            #container {
                padding: 20px;
            }
        }

        /* Animation for values */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .value-updated {
            animation: pulse 0.5s ease;
        }
    </style>
</head>
<body>
    <main>
        <section id="container">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-thermometer-half"></i>
                        <h3 class="card-title">Temperature</h3>
                    </div>
                    <p class="card-value text-suhu" id="suhu">?°C</p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-tint"></i>
                        <h3 class="card-title">Humidity</h3>
                    </div>
                    <p class="card-value text-kelembapan" id="kelembapan">?%</p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-cog"></i>
                        <h3 class="card-title">Servo Position</h3>
                    </div>
                    <input type="range" min="0" max="180" id="servo-slider" class="slider">
                    <p class="card-value text-posisi-servo" id="servo-text">?°</p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-desktop"></i>
                        <h3 class="card-title">LCD Display</h3>
                    </div>
                    <input type="text" name="text-lcd" id="input-lcd" placeholder="Enter text...">
                    <button type="button" id="btn-submit">Update Display</button>
                </div>
            </div>

            <div class="row">
                <div class="card card-table">
                    <div class="card-header">
                        <i class="fas fa-microchip"></i>
                        <h3 class="card-title">Device Status</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Device ID</th>
                                <th>Status</th>
                                <th>Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($devices as $item)
                                <tr>
                                    <td>
                                        <span>{{ $item->serial_number }}</span>
                                    </td>
                                    <td>
                                        <span class="status-offline" id="nusabot/serial_number/{{ $item->serial_number }}">Offline</span>
                                    </td>
                                    <td id="last-update-{{ $item->serial_number }}">
                                        Never
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script>
        const clientId = 'dashboard_' + Math.random().toString(16).substring(2,8)
        const host = "wss://dashboard-iot.cloud.shiftr.io:443/mqtt"

        const options = {
            keepalive: 30,
            clientId: clientId,
            protocolId: 'MQTT',
            protocolVersion: 4,
            username: 'dashboard-iot',
            password: 'zMFFSQNxLvQ29Alg',
            clean: true,
            reconnectPeriod: 1000,
            connectTimeout: 30 * 1000
        }

        console.log("Connecting to broker...");
        const client = mqtt.connect(host, options);
        client.subscribe("nusabot/#", {qos: 1});

        client.on("connect", () => {
            console.log("Connected to broker");
        })

        client.on("message", (topic, message) => {
            const now = new Date();
            const timestamp = now.toLocaleTimeString();

            if(topic === "nusabot/suhu"){
                updateValueWithAnimation("suhu", message + " °C");
            }
            if(topic === "nusabot/kelembapan"){
                updateValueWithAnimation("kelembapan", message + " %");
            }
            if(topic === "nusabot/lcd"){
                document.getElementById("input-lcd").value = message;
            }

            if(topic === "nusabot/servo"){
                updateValueWithAnimation("servo-text", message + "°");
                document.getElementById("servo-slider").value = parseInt(message);
            }

            @foreach ($devices as $item)
                if(topic === "nusabot/serial_number/{{ $item->serial_number }}"){
                    const statusElement = document.getElementById("nusabot/serial_number/{{ $item->serial_number }}");
                    const lastUpdateElement = document.getElementById("last-update-{{ $item->serial_number }}");

                    if(message.toString() === "Online"){
                        statusElement.className = "status-online";
                        statusElement.textContent = "Online";
                        lastUpdateElement.textContent = timestamp;
                    } else {
                        statusElement.className = "status-offline";
                        statusElement.textContent = "Offline";
                        lastUpdateElement.textContent = timestamp;
                    }
                }
            @endforeach
        })

        function updateValueWithAnimation(elementId, newValue) {
            const element = document.getElementById(elementId);
            element.textContent = newValue;
            element.classList.add('value-updated');

            setTimeout(() => {
                element.classList.remove('value-updated');
            }, 500);
        }

        const servoSlider = document.getElementById('servo-slider');
        const textServo = document.getElementById('servo-text');

        servoSlider.addEventListener('input', () => {
            textServo.textContent = `${servoSlider.value}°`;
        });

        servoSlider.addEventListener('mouseup', () => {
            client.publish("nusabot/servo", servoSlider.value.toString(), {qos: 1, retain: true});
        });

        const btnSubmit = document.getElementById('btn-submit');
        const inputLcd = document.getElementById('input-lcd');

        btnSubmit.addEventListener('click', () => {
            const textValue = inputLcd.value.trim();

            if (!textValue) {
                showAlert('Please enter text for the LCD display', 'warning');
            } else {
                client.publish("nusabot/lcd", textValue.toString(), {qos: 1, retain: true});
                showAlert('LCD display updated successfully!', 'success');
            }
        });

        function showAlert(message, type) {
            // In a real application, you might want to implement a proper toast notification system
            alert(message);
        }
    </script>
</body>
</html>
