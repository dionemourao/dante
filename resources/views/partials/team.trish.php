<div class="team">
    <h2>Nossa Equipe</h2>
    
    <div class="team-members">
        @foreach($team ?? [] as $member)
            <div class="team-member">
                <h3>{{ $member['name'] }}</h3>
                <p>{{ $member['role'] }}</p>
                
                @if(isset($member['bio']))
                    <p class="bio">{{ $member['bio'] }}</p>
                @endif
            </div>
        @endforeach
        
        @if(empty($team ?? []))
            <p>Nenhum membro da equipe cadastrado.</p>
        @endif
    </div>
</div>