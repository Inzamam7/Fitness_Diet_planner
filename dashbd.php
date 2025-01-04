<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(60, 34, 34);
            background-image: linear-gradient(to right, #000000, #611717, #bc0606);
            color: white;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #800000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .progress {
            margin-top: 20px;
        }
        .progress-bar {
            width: 100%;
            background-color: #ccc;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 5px;
        }
        .progress-fill {
            height: 20px;
            background-color: #800000;
            text-align: center;
            color: white;
            line-height: 20px;
            transition: width 0.3s;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Dashboard</h1>
    <div id="userPlan"></div>

    <!-- Workout Plan Table -->
    <h3>Workout Plan:</h3>
    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Workout</th>
            </tr>
        </thead>
        <tbody id="workoutPlanTableBody"></tbody>
    </table>

    <!-- Diet Plan Table -->
    <h3>Diet Plan:</h3>
    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Diet</th>
            </tr>
        </thead>
        <tbody id="dietPlanTableBody"></tbody>
    </table>

    <!-- Progress Tracking -->
    <div class="progress">
        <h4>Workout Progress: <span id="workoutProgress">0%</span></h4>
        <div class="progress-bar">
            <div class="progress-fill workout"></div>
        </div>
        <h4>Diet Progress: <span id="dietProgress">0%</span></h4>
        <div class="progress-bar">
            <div class="progress-fill diet"></div>
        </div>
    </div>
</div>

<script>
    // Sample plan data (in a real scenario, this can be fetched from a server or local storage)
    const planData = {
        name: "John Doe",
        age: 25,
        gender: "male",
        dietPreference: "vegan",
        planType: "monthly",
        workoutPlan: "Push-ups, Squats, Lunges",
        dietPlan: "Breakfast: Oats, Lunch: Salad, Dinner: Vegetables"
    };

    // Display user's plan
    document.getElementById('userPlan').innerHTML = `
        <h3>Welcome, ${planData.name}!</h3>
        <p>Here is your saved fitness plan:</p>
        <ul>
            <li><strong>Age:</strong> ${planData.age}</li>
            <li><strong>Gender:</strong> ${planData.gender.charAt(0).toUpperCase() + planData.gender.slice(1)}</li>
            <li><strong>Diet Preference:</strong> ${planData.dietPreference.charAt(0).toUpperCase() + planData.dietPreference.slice(1)}</li>
            <li><strong>Plan Type:</strong> ${planData.planType.charAt(0).toUpperCase() + planData.planType.slice(1)}</li>
        </ul>
    `;

    // Function to populate plan tables
    function populateTable(plan, tableId) {
        const items = plan.split(',').map(item => item.trim());
        const tableBody = document.getElementById(tableId);
        items.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="checkbox" class="${tableId === 'workoutPlanTableBody' ? 'workout-checkbox' : 'diet-checkbox'}" onclick="updateProgress()"></td>
                <td>${item}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Populate workout and diet plan tables
    populateTable(planData.workoutPlan, 'workoutPlanTableBody');
    populateTable(planData.dietPlan, 'dietPlanTableBody');

    // Function to update progress
    function updateProgress() {
        const workoutCheckboxes = document.querySelectorAll('.workout-checkbox');
        const dietCheckboxes = document.querySelectorAll('.diet-checkbox');

        const workoutProgress = calculateProgress(workoutCheckboxes);
        const dietProgress = calculateProgress(dietCheckboxes);

        document.getElementById('workoutProgress').innerText = `${workoutProgress}%`;
        document.getElementById('dietProgress').innerText = `${dietProgress}%`;

        document.querySelector('.progress-fill.workout').style.width = `${workoutProgress}%`;
        document.querySelector('.progress-fill.diet').style.width = `${dietProgress}%`;
    }

    // Function to calculate progress
    function calculateProgress(checkboxes) {
        const total = checkboxes.length;
        const completed = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
        return total > 0 ? ((completed / total) * 100).toFixed(2) : 0;
    }
</script>

</body>
</html>
