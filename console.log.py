from datetime import datetime
import ctypes
import platform

def show_notification(title="Hikari Auto Generator", message=""):
    if platform.system() == 'Windows':
        try:
            MessageBox = ctypes.windll.user32.MessageBoxW
            MessageBox(None, message, title, 0x40)  # 0x40 for INFO
        except:
            # Fallback to console if MessageBox fails
            _console_notification(title, message)
    else:
        # For non-Windows systems
        _console_notification(title, message)

def _console_notification(title, message):
    print(f"\n[{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}]")
    print(f"🔔 {title}")
    print(f"📝 {message}")

if __name__ == "__main__":
    show_notification("Hikari Auto Generator", "System is ready to use! Thank you for installing.")
