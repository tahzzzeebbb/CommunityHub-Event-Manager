<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$role = $_SESSION['role'] ?? '';
$community_id = $_SESSION['community_id'] ?? 0;

// Only admins or organizers allowed
if (!in_array($role, ['admin', 'organizer'])) {
    echo "You don't have permission to create events.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Event</title>
  <link rel="stylesheet" href="css/create_event.css" />
</head>
<body>
  <div class="create-event-container">
    <h2>Create a New Event</h2>

    <form id="create-event-form">
      <label>
        Event Title:
        <input type="text" name="title" required />
      </label>

      <label>
        Description:
        <textarea name="description" rows="4" required></textarea>
      </label>

      <label>
        Date & Time:
        <input type="datetime-local" name="event_datetime" required />
      </label>

      <label>
        Location:
        <input type="text" name="location" required />
      </label>

      <label>
        Capacity:
        <input type="number" name="capacity" min="1" required />
      </label>

      <button type="submit">Create Event</button>
    </form>

    <div id="message"></div>
  </div>

  <script>
    const form = document.getElementById('create-event-form');
    const message = document.getElementById('message');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData(form);

      const payload = {
        title: formData.get('title').trim(),
        description: formData.get('description').trim(),
        event_datetime: formData.get('event_datetime'),
        location: formData.get('location').trim(),
        capacity: Number(formData.get('capacity'))
      };

      try {
        const res = await fetch('modules/events/create.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (res.ok) {
          message.textContent = 'Event created successfully!';
          message.style.color = 'green';
          form.reset();
        } else {
          message.textContent = data.error || 'Failed to create event.';
          message.style.color = 'red';
        }
      } catch (err) {
        message.textContent = 'Network error.';
        message.style.color = 'red';
      }
    });
  </script>
</body>
</html>
