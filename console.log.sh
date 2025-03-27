#!/bin/bash

show_notification() {
    title="${1:-Hikari Auto Generator}"
    message="${2:-Alert message}"
    
    # Check OS and use appropriate notification method
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        osascript -e "display notification \"$message\" with title \"$title\""
    elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
        # Linux
        if command -v notify-send >/dev/null 2>&1; then
            notify-send "$title" "$message"
        else
            echo -e "\n[$(date '+%Y-%m-%d %H:%M:%S')]"
            echo "🔔 $title"
            echo "📝 $message"
        fi
    else
        # Windows or other OS - fallback to echo
        echo -e "\n[$(date '+%Y-%m-%d %H:%M:%S')]"
        echo "🔔 $title"
        echo "📝 $message"
    fi
}

# Show welcome notification
show_notification "Hikari Auto Generator" "System is initialized and ready!"
