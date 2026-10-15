# Script to write dashboard.blade.php content
import os

output_path = os.path.join('resources', 'views', 'dashboard.blade.php')

# The content is too large to pass directly in command line,
# so we'll append to the file in chunks
