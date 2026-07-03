<?php
$lastUpdated = "03 July 2026";
$appName = "CMMS Mobile";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | <?= $appName ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B91C1C',
                        secondary: '#991B1B',
                        accent: '#EF4444'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-gray-100 via-white to-red-50">

<div class="max-w-6xl mx-auto px-6 py-16">

    <!-- Header -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="bg-gradient-to-r from-primary via-secondary to-primary text-white p-12">

            <div class="flex items-center justify-between flex-wrap gap-6">

                <div>
                    <h1 class="text-5xl font-extrabold tracking-wide">
                        Privacy Policy
                    </h1>

                    <p class="mt-3 text-red-100 text-lg">
                        Computerized Maintenance Management System
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-lg px-8 py-5 rounded-2xl border border-white/20">

                    <p class="text-sm uppercase tracking-widest text-red-100">
                        Last Updated
                    </p>

                    <h2 class="text-2xl font-bold mt-1">
                        <?= $lastUpdated ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="p-12 space-y-12">

            <!-- About -->
            <section>

                <h2 class="text-3xl font-bold text-gray-800 mb-5">
                    About <?= $appName ?>
                </h2>

                <p class="text-gray-600 leading-8 text-lg">
                    <?= $appName ?> is a Computerized Maintenance Management System (CMMS)
                    designed to support maintenance management activities, equipment monitoring,
                    work order management, preventive maintenance scheduling,
                    and maintenance documentation in an efficient, secure,
                    and integrated manner.
                </p>

            </section>

            <!-- Information -->
            <section>

                <h2 class="text-3xl font-bold text-gray-800 mb-5">
                    Information We Collect
                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <div class="bg-red-50 border-l-4 border-primary rounded-xl p-6">

                        <h3 class="font-bold text-xl mb-3">
                            Account Information
                        </h3>

                        <ul class="space-y-2 text-gray-700">
                            <li>• Name</li>
                            <li>• Email Address</li>
                            <li>• User Role</li>
                            <li>• Department</li>
                        </ul>

                    </div>

                    <div class="bg-red-50 border-l-4 border-primary rounded-xl p-6">

                        <h3 class="font-bold text-xl mb-3">
                            Maintenance Documentation
                        </h3>

                        <ul class="space-y-2 text-gray-700">
                            <li>• Equipment Photos</li>
                            <li>• Maintenance Evidence</li>
                            <li>• Damage Documentation</li>
                            <li>• Uploaded Images</li>
                        </ul>

                    </div>

                </div>

            </section>

            <!-- Usage -->
            <section>

                <h2 class="text-3xl font-bold text-gray-800 mb-5">
                    How We Use Your Information
                </h2>

                <div class="bg-gray-50 rounded-2xl p-8">

                    <ul class="space-y-4 text-gray-700 leading-8">

                        <li>✔ Authenticate users securely.</li>

                        <li>✔ Manage maintenance activities.</li>

                        <li>✔ Store maintenance history and documentation.</li>

                        <li>✔ Upload equipment images and maintenance evidence.</li>

                        <li>✔ Improve system performance and security.</li>

                    </ul>

                </div>

            </section>

            <!-- Permissions -->

            <section>

                <h2 class="text-3xl font-bold text-gray-800 mb-5">
                    Application Permissions
                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <div class="bg-white shadow-lg rounded-xl p-6 border">

                        <h3 class="font-bold text-xl mb-3 text-primary">
                            Camera
                        </h3>

                        <p class="text-gray-600">
                            Used for capturing maintenance evidence and equipment photos.
                        </p>

                    </div>

                    <div class="bg-white shadow-lg rounded-xl p-6 border">

                        <h3 class="font-bold text-xl mb-3 text-primary">
                            Photos & Media
                        </h3>

                        <p class="text-gray-600">
                            Used to select existing images from the device gallery
                            for maintenance documentation.
                        </p>

                    </div>

                </div>

            </section>

            <!-- Security -->

            <section>

                <h2 class="text-3xl font-bold text-gray-800 mb-5">
                    Data Security
                </h2>

                <p class="text-gray-700 leading-8 text-lg">

                    We implement appropriate technical and administrative safeguards
                    to protect user information against unauthorized access,
                    alteration, disclosure, or destruction.

                </p>

            </section>

            <!-- Developers -->

            <section>

                <h2 class="text-3xl font-bold text-gray-800 mb-6">
                    Application Developers
                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <div class="rounded-2xl bg-gradient-to-r from-red-600 to-red-700 text-white p-8 shadow-xl">

                        <h3 class="text-2xl font-bold">
                            Muhammad Naufal Hibatullah
                        </h3>

                        <p class="mt-3 text-red-100">
                            Full Stack Developer
                        </p>

                    </div>

                    <div class="rounded-2xl bg-gradient-to-r from-red-600 to-red-700 text-white p-8 shadow-xl">

                        <h3 class="text-2xl font-bold">
                            Arya Furi Eka Saputra
                        </h3>

                        <p class="mt-3 text-red-100">
                            Full Stack Developer
                        </p>

                    </div>

                </div>

            </section>

            <!-- Contact -->

            <section>

                <div class="rounded-3xl bg-gradient-to-r from-primary to-secondary text-white p-10">

                    <h2 class="text-3xl font-bold mb-4">
                        Contact Information
                    </h2>

                    <p class="text-red-100 text-lg">
                        If you have any questions regarding this Privacy Policy,
                        please contact us through the official communication channel
                        of the CMMS Mobile application.
                    </p>

                </div>

            </section>

        </div>

    </div>

    <div class="text-center mt-10 text-gray-500">

        © <?= date('Y') ?> <?= $appName ?>

        <br>

        Developed by
        <strong>Muhammad Naufal Hibatullah</strong>
        &
        <strong>Arya Furi Eka Saputra</strong>

    </div>

</div>

</body>
</html>
