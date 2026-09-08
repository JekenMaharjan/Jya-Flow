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

    .task-detail {
        border-top: 1px solid #e5e7eb; 
        padding-top: 12px; 
        font-size: 14px; 
        color: #6b7280;
    }

    .task-priority {
        display: inline-block; 
        padding: 2px 8px; 
        background-color: #e0e7ff; 
        color: #3730a3; 
        border-radius: 9999px; 
        font-weight: 600; 
        font-size: 12px;
    }

    .task-due {
        display: inline-block; 
        padding: 2px 8px; 
        background-color: #d1fae5; 
        color: #065f46; 
        border-radius: 9999px; 
        font-weight: 600; 
        font-size: 12px;
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
    }
</style>


<div class="email-wrapper">
    <div class="email-card">
        <!-- Header -->
        <div class="header">
            <h1 class="header-title">
                Hello, {{ $task->user->name }}!
            </h1>
        </div>

        <!-- Body Content -->
        <div class="body">
            <p class="body-title">
                Your task has been created successfully! Here are the details:
            </p>

            <!-- Task Card -->
            <div class="task-card">
                <h2 class="task-title">
                    {{ $task->title }}
                </h2>
                
                @if($task->description)
                    <p class="task-description">
                        {{ $task->description }}
                    </p>
                @endif

                <div class="task-detail">
                    <p>
                        <strong>Priority:</strong> 

                        <span class="task-priority">
                            {{ ucfirst($task->priority->value ?? $task->priority) }}
                        </span>
                    </p>

                    <p>
                        <strong>Due Date:</strong> 
                        
                        <span class="task-due">
                            {{ ($task->due_at)->format('M d, Y \a\t g:i A') }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Call to Action Button -->
            <div class="btn-div">
                <a href="{{ url('/tasks/' . $task->id) }}" class="btn">
                    View Your Task Listing
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Sent automatically by {{ config('app.name') }}
        </div>
    </div>
</div>