#!/bin/bash

# Ensure nav*.adoc files exist
shopt -s nullglob

files=(nav*.adoc)

if [ ${#files[@]} -eq 0 ]; then
  echo "No files matching 'nav*.adoc' found in the current directory."
  exit 1
fi

# Loop through each pattern in 'all-petzen'
while IFS= read -r pattern || [ -n "$pattern" ]; do
  echo "Searching for pattern: \"$pattern\""

  # Search and capture output
  matches=$(pcregrep -nM "$pattern" "${files[@]}")

  if [ -n "$matches" ]; then
    echo "✅ Pattern found:"
    echo "$matches"
  else
    echo "❌ Pattern NOT found in any nav*.adoc file."
  fi

  echo "----------------------------------------"
done < all-band1a

