# Rollback Script for Feature Image Update
# This script will revert the changes pushed for the feature image upload enhancement.
# It uses 'git revert' to create a new commit that safely undoes the last commit,
# which is the recommended way to rollback changes that have already been pushed to the remote server.

Write-Host "Rolling back the Feature update commit (c0ff1cb)..."

# Revert the specific commit without immediately committing
git revert --no-commit c0ff1cb

# Commit the rollback
git commit -m "Rollback Feature form update"

Write-Host "Rollback commit created locally. To push this rollback to the server, run: git push origin production-refactor"
