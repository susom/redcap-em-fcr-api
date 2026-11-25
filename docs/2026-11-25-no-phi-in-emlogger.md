# Stop logging participant passcodes and submitted data to em_logger (2026-11-25)

## Problem

- **`service.php` (every API call).** It called `emLog($_REQUEST, "Incoming Request")`. This module's own `emLog()` always ships at INFO. The request holds the participant id, the passcode and the submitted `data` JSON, and they were logged even for rejected requests.
- **Debug lines** logged:
  - the whole `$_POST`;
  - the passcode, on both a match and a mismatch;
  - on a mismatch, the record that was read (including its stored `pw`);
  - the data being saved.

## Fix

- `service.php` emDebugs only the action.
- `parseInput()` logs the POST keys.
- The match/mismatch lines log the participant id only.
- SaveData logs the field names.

## How it was reproduced and verified (local, REDCap 17.5.9, throwaway PID 272)

The no-auth `service` page was POSTed with a fake participant id, passcode and data, as the mobile app calls it. `fcr_api.json` was then checked.

| Code | Lines with passcode / data |
| --- | --- |
| `master` | 1 (INFO, on a rejected request) |
| this branch, debug off | 0 (no lines) |
| this branch, system debug on | 0 of 6 |

The match/mismatch lines need a configured FCR project with `id`/`pw` records, so they were changed by review. Script: `emlogger-phi-review/e2e/fcr-api.sh` in the REDCap EM workspace.
