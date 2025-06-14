<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IoT Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #3a86ff;
            --success: #4cc9f0;
            --danger: #ff5a5f;
            --accent: #8338ec;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --white: #ffffff;
        }

        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .dashboard-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--gray);
        }

        .card-icon {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            color: var(--primary);
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .temperature {
            color: var(--danger);
        }

        .humidity {
            color: var(--success);
        }

        .servo {
            color: var(--accent);
        }

        .slider-container {
            width: 100%;
            margin: 1rem 0;
        }

        .slider {
            width: 100%;
            height: 6px;
            -webkit-appearance: none;
            background: #e0e0e0;
            border-radius: 3px;
            outline: none;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .slider::-webkit-slider-thumb:hover {
            background: var(--accent);
        }

        .input-group {
            margin-top: 1rem;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
            transition: border 0.2s ease;
        }

        .input-field:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .btn:hover {
            background: var(--accent);
        }

        .devices-table {
            width: 100%;
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            background: #f8f9fa;
            font-weight: 500;
            color: var(--gray);
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-online {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-offline {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .online-dot {
            background: #28a745;
        }

        .offline-dot {
            background: #dc3545;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .updated {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1 class="dashboard-title">IoT Dashboard</h1>
        </header>

        <div class="card-grid">
            <!-- Temperature Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-thermometer-half card-icon"></i>
                    <h3 class="card-title">Temperature</h3>
                </div>
                <p class="card-value temperature" id="suhu">—°C</p>
            </div>

            <!-- Humidity Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tint card-icon"></i>
                    <h3 class="card-title">Humidity</h3>
                </div>
                <p class="card-value humidity" id="kelembapan">—%</p>
            </div>

            <!-- Servo Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cog card-icon"></i>
                    <h3 class="card-title">Servo Position</h3>
                </div>
                <p class="card-value servo" id="servo-text">—°</p>
                <div class="slider-container">
                    <input type="range" min="0" max="180" value="90" class="slider" id="servo-slider">
                </div>
            </div>

            <!-- LCD Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-desktop card-icon"></i>
                    <h3 class="card-title">LCD Display</h3>
                </div>
                <div class="input-group">
                    <input type="text" class="input-field" id="input-lcd" placeholder="Enter message...">
                    <button class="btn" id="btn-submit">Update</button>
                </div>
            </div>
        </div>

        <!-- Devices Table -->
        <div class="card">
            <table class="devices-table">
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
                        <td>{{ $item->serial_number }}</td>
                        <td>
                            <span class="status status-offline" id="nusabot/serial_number/{{ $item->serial_number }}">
                                <span class="status-dot offline-dot"></span>
                                Offline
                            </span>
                        </td>
                        <td id="last-update-{{ $item->serial_number }}">—</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<script>
    const lastValues = {
        "nusabot/suhu": null,
        "nusabot/kelembapan": null,
    };

    const mqttClient = {
        saveToDatabase: function (value, topic, namaSensor = "Sensor") {
            // Cek apakah data sama dengan sebelumnya
            if (lastValues[topic] === value) {
                console.log(`Data ${namaSensor} sama, tidak dikirim.`);
                return;
            }

            // Simpan nilai terakhir
            lastValues[topic] = value;

            fetch("/api/sensor", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    nama_sensor: namaSensor,
                    data: value,
                    topic: topic
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err });
                    }
                    return response.json();
                })
                .then((data) => console.log("Saved to database:", data))
                .catch((error) => console.error("Error saving to database:", error));
        },

        loadInitialData: function () {
            fetch("/api/esp32/latest")
                .then((response) => response.json())
                .then((data) => {
                    if (data.temperature !== null) {
                        updateDisplay('suhu', `${data.temperature}°C`);
                        lastValues["nusabot/suhu"] = data.temperature.toString();
                    }
                    if (data.humidity !== null) {
                        updateDisplay('kelembapan', `${data.humidity}%`);
                        lastValues["nusabot/kelembapan"] = data.humidity.toString();
                    }
                })
                .catch((error) =>
                    console.error("Error loading initial data:", error)
                );
        },

        handleMessage: function (topic, message) {
            const value = message.toString();
            const now = new Date().toLocaleTimeString();

            if (topic === "nusabot/suhu") {
                updateDisplay('suhu', `${value}°C`);
                this.saveToDatabase(value, topic, "Suhu");
            } else if (topic === "nusabot/kelembapan") {
                updateDisplay('kelembapan', `${value}%`);
                this.saveToDatabase(value, topic, "Kelembapan");
            } else if (topic === "nusabot/servo") {
                updateDisplay('servo-text', `${value}°`);
                document.getElementById('servo-slider').value = value;
            } else if (topic === "nusabot/lcd") {
                document.getElementById('input-lcd').value = value;
            }

            @foreach ($devices as $item)
                if (topic === "nusabot/serial_number/{{ $item->serial_number }}") {
                    const statusElement = document.getElementById(`nusabot/serial_number/{{ $item->serial_number }}`);
                    const lastUpdateElement = document.getElementById(`last-update-{{ $item->serial_number }}`);

                    if (value === "Online") {
                        statusElement.className = "status status-online";
                        statusElement.innerHTML = '<span class="status-dot online-dot"></span> Online';
                    } else {
                        statusElement.className = "status status-offline";
                        statusElement.innerHTML = '<span class="status-dot offline-dot"></span> Offline';
                    }
                    lastUpdateElement.textContent = now;
                }
            @endforeach
        }
    };

    // MQTT Client Setup
    const client = mqtt.connect("wss://dashboard-iot.cloud.shiftr.io:443/mqtt", {
        username: 'dashboard-iot',
        password: 'zMFFSQNxLvQ29Alg',
        clientId: 'web_' + Math.random().toString(16).substr(2, 8)
    });

    client.on('connect', () => {
        console.log('Connected to MQTT broker');
        client.subscribe("nusabot/#");
        mqttClient.loadInitialData();
    });

    client.on('message', (topic, message) => {
        mqttClient.handleMessage(topic, message);
    });

    // Update display
    function updateDisplay(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
            element.classList.add('updated');
            setTimeout(() => element.classList.remove('updated'), 300);
        }
    }

    // Servo slider interaction
    const servoSlider = document.getElementById('servo-slider');
    if (servoSlider) {
        servoSlider.addEventListener('change', () => {
            const value = servoSlider.value;
            document.getElementById('servo-text').textContent = `${value}°`;
            client.publish("nusabot/servo", value);
        });
    }

    // LCD input handling
    const lcdBtn = document.getElementById('btn-submit');
    if (lcdBtn) {
        lcdBtn.addEventListener('click', () => {
            const message = document.getElementById('input-lcd').value.trim();
            if (message) {
                client.publish("nusabot/lcd", message);
            }
        });
    }
</script>

</body>
</html>
