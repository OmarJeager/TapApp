<div style="font-family: Arial, sans-serif; max-width: 520px; margin: auto; padding: 24px;">
    <h2 style="color: {{ $isActive ? '#15803d' : '#b91c1c' }};">
        {{ $isActive ? 'Account Activated' : 'Account Deactivated' }}
    </h2>

    <p>Hello {{ $user->name }},</p>

    @if($isActive)
        <p>Your TapApp account has been <strong>activated</strong> by the CEO. You can now log in with your usual credentials.</p>
    @else
        <p>Your TapApp account has been <strong>deactivated</strong> by the CEO. You cannot log in while it is inactive. Please contact your administrator if you need assistance.</p>
    @endif

    <p style="color:#888; font-size:12px;">TapApp</p>
</div>
