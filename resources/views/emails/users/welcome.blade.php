<style>
    .email-wrapper {
        background-color: #f3f4f6; 
        padding: 32px 16px; 
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .email-card {
        max-width: 600px; 
        margin: 0 auto; 
        background-color: #ffffff; 
        border-radius: 12px; 
        overflow: hidden; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .header {
        background-color: #6366f1; 
        padding: 24px; 
        text-align: center;
    }

    .header-title {
        color: #ffffff; 
        font-size: 24px; 
        font-weight: 700; 
        margin: 0;
    }

    .body {
        padding: 24px;
    }

    .body-title {
        color: #374151; 
        font-size: 16px; 
        margin-top: 0; 
        margin-bottom: 20px;
    }

    .task-card {
        background-color: #f9fafb; 
        border: 1px solid #e5e7eb; 
        border-radius: 8px; 
        padding: 20px; 
        margin-bottom: 24px;
    }

    .btn {
        background-color: #4f46e5; 
        color: #ffffff; 
        padding: 12px 24px; 
        border-radius: 6px; 
        font-weight: 600; 
        text-decoration: none; 
        display: inline-block; 
        font-size: 14px;
    }

    .task-title {
        margin-top: 0; 
        color: #111827; 
        font-size: 18px; 
        font-weight: 600;
    }

    .task-description {
        color: #4b5563; 
        font-size: 14px; 
        margin-bottom: 16px;
    }

    .task-detail p {
        margin: 4px 0;
    }

    .task-detail strong {
        color: #374151;
    }

    .btn-div {
        text-align: center; 
        margin-top: 28px;
    }

    .footer {
        background-color: #f9fafb; 
        padding: 16px; 
        text-align: center; 
        border-top: 1px solid #e5e7eb; 
        font-size: 12px; 
        color: #9ca3af;
        font-style: italic;
    }
</style>


<div class="email-wrapper">
    <div class="email-card">
        <!-- Header -->
        <div class="header">
            <h1 class="header-title">
                Hello, {{ $user->name }}!
            </h1>
        </div>

        <!-- Body Content -->
        <div class="body">
            <p class="body-title">
                Welcome to {{ config('app.name') }}, {{ $user->name }}!
            </p>

            <!-- Task Card -->
            <div class="task-card">
                <h2 class="task-title">
                    Thanks for creating an account with us. We're excited to have you on board!
                </h2>
                
                <p class="task-description">
                    You made it! Say goodbye to scattered sticky notes and missed deadlines. Your account is live, and your fresh start begins today.
                </p>
            </div>

            <!-- Call to Action Button -->
            <div class="btn-div">
                <a href="{{ route('login') }}" class="btn">
                    Log In to Your Workspace
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Set up your workspace. Conquer your tasks list.
        </div>
    </div>
</div>