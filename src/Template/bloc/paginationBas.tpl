{% if nbPage > 1 %}
<p class="pagination paginationBas">
	{% if 1 != page %}
		<a href="{{ 2 != page ? page-1~'/' : '' }}{{UrlCourant}}{{UrlTri}}" title="Page n°{{page-1}}">&laquo; précédents</a> - 
	{% endif %}
	 page 
	{% set pointSuspensionDejaAffiche = false %}			
	{% for i in 1..nbPage %}
		{# Lien si on n'est pas sur cette page #}
		{% if i != page %}
			{% if i <= nbMaxLienPagination 
				or i >= (nbPage - nbMaxLienPagination + 1)
				or (i >= (page - nbMaxLienPagination) and i <= (page + nbMaxLienPagination))
			%}
				<a href="{{ i != 1 ? i~'/' : '' }}{{UrlCourant}}{{UrlTri}}" title="Page n°{{i}}">{{i}}</a> 
			{% elseif false == pointSuspensionDejaAffiche %}
				...
				{% set pointSuspensionDejaAffiche = true %}	
			{% endif %}
		{# Sans lien si on est sur cette page #}
		{% else %}
			<strong>{{i}}</strong>
			{% set pointSuspensionDejaAffiche = false %}	
		{% endif %}
	{% endfor %}
	{% if page != nbPage %}
		- <a href="{{page+1}}/{{UrlCourant}}{{UrlTri}}" title="Page n°{{page+1}}">suivants &raquo;</a>
	{% endif %}
</p>
{% endif %}