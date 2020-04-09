
workflow "PHP Linting" {
  resolves = ["Execute"]
  on = "pull_request"
}

action "Execute" {
  uses = "michaelw90/PHP-Lint@master"
}
