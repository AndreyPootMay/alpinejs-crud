<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpine JS CRUD</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div x-data="{title: 'The crud are inevitable'}">
        <div x-text="title"></div>

        <input type="text" x-model="title">

        <button x-on:click="title='Hi teacher I am your CRUD'">
            Hi teacher!
        </button>

        <template x-if="title === 'crud'">
            <div>Subscribe to my channel</div>
        </template>
    </div>
</body>
</html>